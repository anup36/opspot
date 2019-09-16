<?php

/**
 * Opspot Blockchain Events
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain;

use Opspot\Core\Blockchain\Events\BlockchainEventInterface;
use Opspot\Core\Blockchain\Events\BoostEvent;
use Opspot\Core\Blockchain\Events\TokenSaleEvent;
use Opspot\Core\Blockchain\Events\WireEvent;
use Opspot\Core\Blockchain\Events\WithdrawEvent;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Events\Event;

class Events
{
    protected static $handlers = [
        TokenSaleEvent::class,
        WireEvent::class,
        BoostEvent::class,
        WithdrawEvent::class
    ];

    public function register()
    {
        Dispatcher::register('blockchain:listen', 'all', function (Event $event) {
            $topicsMap = [];

            foreach (static::$handlers as $handlerClass) {
                /** @var BlockchainEventInterface $handler */
                $handler = new $handlerClass();
                $topics = $handler->getTopics();

                if (!is_array($topics)) {
                    $topics = [ $topics ];
                }

                foreach ($topics as $topic) {
                    if (!isset($topicsMap[$topic])) {
                        $topicsMap[$topic] = [];
                    }

                    $topicsMap[$topic][] = $handlerClass;
                }
            }

            $event->setResponse($topicsMap);
        });
    }
}
