<?php
namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Events\Dispatcher;

/**
* Queued Subscriptions
*/
class SubscriptionDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setExchange('opspotqueue', 'direct')
            ->setQueue('SubscriptionDispatcher')
            ->receive(function ($data) {
                $data = $data->getData();
                echo "\nDispatching async subscription for {$data['currentUser']}...";
                $result = Dispatcher::trigger('subscription:dispatch', 'all', $data);

                // print_r($result);
            });
    }
}
