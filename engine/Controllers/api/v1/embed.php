<?php
/**
 * Opspot Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Entities\Factory as EntitiesFactory;

class embed implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        define('__OPSPOT_CONTEXT__', 'embed');

        if (!$pages[0]) {
            $embedded_entity = null;
        } else {
            $embedded_entity = EntitiesFactory::build($pages[0]);

            if ($embedded_entity) {
                $embedded_entity = $embedded_entity->export();
            }
        }
        include dirname(__OPSPOT_ROOT__) . implode(DIRECTORY_SEPARATOR, [ '', 'front', 'dist', 'en', 'index.php' ]);
    }

    public function post($pages)
    {
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
