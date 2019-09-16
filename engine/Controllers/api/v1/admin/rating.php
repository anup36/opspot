<?php
/**
 * Opspot Content Rating API
 *
 * @version 1
 * @author Marcelo
 */

namespace Opspot\Controllers\api\v1\admin;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Queue;
use Opspot\Entities\Factory as EntitiesFactory;
use Opspot\Interfaces;
use Opspot\Core\Entities\Actions\Save;
use Opspot\Core\Di\Di;

class rating implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send a GUID.']);
        }
        if (!isset($pages[1])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must send a valid content rating (1 = safe, 2 = open).'
            ]);
        }
        $rating = intval($pages[1]);
        if ($rating !== 1 && $rating !== 2) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Content rating value can only be 1 (safe) or 2 (open).'
            ]);
        }
        $entity = EntitiesFactory::build($pages[0]);

        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'Entity not found.']);
        }

        $entity->setRating($rating);
        
        $save = new Save();
        $save->setEntity($entity)
            ->save();

        /** @var Core\Events\Dispatcher $dispatcher */
        $dispatcher = Di::_()->get('EventsDispatcher');
        $dispatcher->trigger('search:index', 'all', [
            'entity' => $entity,
            'immediate' => true
        ]);

        Queue\Client::Build()->setQueue("Trending")
            ->send(['a']);

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }


}
