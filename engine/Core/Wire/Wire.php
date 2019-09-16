<?php
/**
 * Wire model
 */
namespace Opspot\Core\Wire;

use Opspot\Core\Guid;
use Opspot\Traits\MagicAttributes;

class Wire
{
    use MagicAttributes;

    /** @var int **/
    private $guid;

    /** @var User **/
    private $receiver;

    /** @var User **/
    private $entity;

    /** @var User **/
    private $sender;

    /** @var string **/
    private $amount;

    /** @var bool **/
    private $recurring = false;

    /** @var method **/
    private $method = 'tokens';

    /** @var int $timestamp **/
    private $timestamp;

    public function getGuid() {
        if (!$this->guid) {
            $this->guid = Guid::build();
        }

        return $this->guid;
    }

    public function export() {
        return [
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'receiver' => $this->receiver->export(),
            'sender' => $this->sender->export(),
            'recurring' => $this->recurring,
        ];
    }

}
