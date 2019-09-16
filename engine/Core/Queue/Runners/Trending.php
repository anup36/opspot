<?php


namespace Opspot\Core\Queue\Runners;

use Opspot\Core;
use Opspot\Core\Queue\Interfaces;

class Trending implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Core\Queue\Client::Build();
        $client->setQueue("Trending")
            ->receive(function ($data) {
                echo "Received a compile trending request \n";
                $manager = new Core\Trending\Manager();
                $manager->run();
            });
    }

}
