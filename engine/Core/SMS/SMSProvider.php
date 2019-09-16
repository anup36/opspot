<?php
/**
 * Opspot SMS Provider
 */

namespace Opspot\Core\SMS;

use Opspot\Core\Di\Provider;
use Opspot\Core\SMS\Services\Twilio;

class SMSProvider extends Provider
{
    public function register()
    {
        $this->di->bind('SMS', function ($di) {
            return new Twilio();
        }, ['useFactory' => true]);
        $this->di->bind('SMS\SNS', function ($di) {
            return new Services\SNS();
        }, ['useFactory' => true]);
    }
}
