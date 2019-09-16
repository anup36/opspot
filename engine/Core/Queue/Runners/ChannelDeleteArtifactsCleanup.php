<?php
namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Data;
use Opspot\Entities;
use Opspot\Core\Channels\Delegates\DeleteArtifacts;

class ChannelDeleteArtifactsCleanup implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("ChannelDeleteArtifactsCleanup")
               ->receive(function ($data) {
                   echo "Received a channel delete feed cleanup request \n";

                   $data = $data->getData();
                   
                   $deleteArtifacts = new DeleteArtifacts;
                   $deleteArtifacts->delete($data['user_guid']);
               });
    }
}
