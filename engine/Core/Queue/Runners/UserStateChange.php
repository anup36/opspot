<?php

namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Events\Dispatcher;

/**
 * User State queue runner.
 */
class UserStateChange implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue('UserStateChanges')
               ->receive(function ($data) use ($mailer) {
                   $data = $data->getData();
                   $result = Dispatcher::trigger('user_state_change', $data['user_state_change']['state'], $data['user_state_change']);
               });
    }
}
