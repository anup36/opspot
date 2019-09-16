<?php
namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Events\Dispatcher;
use Opspot\Entities\Group as GroupEntity;
use Opspot\Core\Groups\Notifications;

/**
* Queued Groups Notifications
*/
class UpdateMarkerDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("UpdateMarkerDispatcher")
            ->receive(function ($data) {
                $data = $data->getData();
                $marker = unserialize($data['marker']);
                
                $group = new GroupEntity();
                $group->loadFromGuid($marker->getEntityGuid());


                echo "[]: updating markers for $group->guid \n";

                $notifications = (new Notifications)->setGroup($group);
                $notifications->send($marker);
                echo "(done)";
            });
    }
}
