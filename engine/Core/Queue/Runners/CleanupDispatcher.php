<?php
/**
 * Cleanup Dispatcher - Used by
 */
namespace Opspot\Core\Queue\Runners;

use Opspot\Core\Queue\Interfaces;
use Opspot\Core\Queue;
use Opspot\Core\Events\Dispatcher;

/**
* Queued Notifications
*/
class CleanupDispatcher implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client
        ->setQueue("CleanupDispatcher")
        ->receive(function ($data) {
            $type = isset($data['type']) ? $data['type'] : 'all';
            Dispatcher::trigger('cleanup:dispatch', $type, $data);
        });
    }
}
