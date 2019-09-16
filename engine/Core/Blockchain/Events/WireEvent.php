<?php

/**
 * Blockchain Wire Events
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain\Events;

use Opspot\Core\Blockchain\Util;
use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;
use Opspot\Core\Wire\Manager;
use Opspot\Core\Wire\Wire;
use Opspot\Entities\User;

class WireEvent implements BlockchainEventInterface
{
    public static $eventsMap = [
        '0xce785fa87dd60f986617d1c5e02218c5b233399cc29e9a326a41a76fabc95d66' => 'wireSent'
    ];

    /** @var Manager $manager */
    private $manager;

    /** @var Config $config */
    private $config;

    public function __construct($manager = null, $config = null)
    {
        $this->manager = $manager ?: Di::_()->get('Wire\Manager');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @return array
     */
    public function getTopics()
    {
        return array_keys(static::$eventsMap);
    }

    /**
     * @param $topic
     * @param array $log
     * @throws \Exception
     */
    public function event($topic, array $log, $transaction)
    {
        $method = static::$eventsMap[$topic];

        if ($log['address'] != $this->config->get('blockchain')['contracts']['wire']['contract_address']) {
            throw new \Exception('Event does not match address');
        }

        if (method_exists($this, $method)) {
            $this->{$method}($log, $transaction);
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function wireSent($log, $transaction)
    {
        // $token = OpspotToken::at(Di::_()->get('Config')->get('blockchain')['token_address']);

        // $tx = $log['transactionHash'];
        list($sender, $receiver, $amount) = Util::parseData($log['data'], [Util::ADDRESS, Util::ADDRESS, Util::NUMBER]);
        $amount = (string) BigNumber::fromHex($amount);

        $data = $transaction->getData();

        $wire = (new Wire)
            ->setAmount($amount)
            ->setRecurring(false)
            ->setSender(new User($data['sender_guid']))
            ->setReceiver(new User($data['receiver_guid']))
            ->setEntity(new User($data['receiver_guid'])) //TODO: make this the entity
            ->setTimestamp($transaction->getTimestamp())
            ->setEntity($data['entity_guid'])
            ->setMethod('tokens');

        try {
            $this->manager->confirm($wire, $transaction);
        } catch (\Exception $e) {
            error_log(print_r($e, true));
        }

    }
}
