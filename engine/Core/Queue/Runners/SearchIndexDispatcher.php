<?php

/**
 * Search Index Dispatcher
 *
 * @author emi
 */

namespace Opspot\Core\Queue\Runners;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Queue;
use Opspot\Core\Queue\Interfaces\QueueRunner;

class SearchIndexDispatcher implements QueueRunner
{
    /**
     * Runs the queue
     */
    public function run()
    {
        $client = Queue\Client::build();

        $client
            ->setQueue("SearchIndexDispatcher")
            ->receive(function ($data) {
                /** @var Core\Events\Dispatcher $dispatcher */
                $dispatcher = Di::_()->get('EventsDispatcher');

                $data = $data->getData();
                $dispatcher->trigger('search:index:dispatch', 'all', $data);
            });
    }
}
