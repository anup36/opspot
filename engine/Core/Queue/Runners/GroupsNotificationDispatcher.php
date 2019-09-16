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
class GroupsNotificationDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("GroupsNotificationDispatcher")
            ->receive(function ($data) {
                $data = $data->getData();
                
                $group = new GroupEntity();
                $group->loadFromGuid($data['entity']);

                $guid = $data['params']['activity'];

                echo "[]: sending $guid to members of $group->guid \n";

                $notifications = (new Notifications)->setGroup($group);
                $notifications->send($data['params']);

                echo "[]: sending $guid to members $group->guid \n";
            });
    }
}
