<?php
namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;

/**
 * Cluster runner
 */

class Cluster implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build("RabbitMQ", array('exchange'=>"exchange_topic"));
        $client->setExchange("topic_demo", "topic")
            ->setQueue("", "ping.*")
            ->receive(function ($data) {
                echo "Received a message";
                var_dump($data->getData());
                echo "\n\n";
            });
    }
}
