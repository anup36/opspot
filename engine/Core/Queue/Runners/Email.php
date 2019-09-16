<?php
namespace Opspot\Core\Queue\Runners;

use Opspot\Core;
use Opspot\Core\Data;
use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Notification\Settings;
use Opspot\Entities\User;
use Surge;

/**
 * Email queue runner
 */

class Email implements Interfaces\QueueRunner
{
    public function run()
    {
        $mailer = new Core\Email\Mailer();
        $client = Queue\Client::Build();
        $client->setQueue("Email")
               ->receive(function ($data) use ($mailer) {
                   echo "[email]: Received an email \n";

                   $data = $data->getData();

                   $message = unserialize($data['message']);

                   $mailer->send($message);

                   echo "[email]: delivered to {$message->to[0]['name']} ($message->subject) \n";
               });
        $this->run();
    }
}
