<?php

/**
 * Description
 *
 * @author emi
 */

namespace Opspot\Core\Search;

use Opspot\Core;

class Queue
{
    /**
     * @param $entity
     * @return bool
     */
    public function queue($entity)
    {
        Core\Queue\Client::build()
            ->setQueue('SearchIndexDispatcher')
            ->send([
                'entity' => serialize($entity)
            ]);

        return true;
    }
}
