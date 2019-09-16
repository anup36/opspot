<?php
/**
 * Opspot Admin: Monetize
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Opspot\Controllers\api\v1\admin;

use Opspot\Core;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class monetize implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        $response = array();
        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * Monetize a post
     * @param array $pages
     */
    public function put($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(array(
              'status' => 'error',
              'message' => "Entity not found"
            ));
        }

        $entity->monetized = true;
        $entity->save();

        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(array(
              'status' => 'error',
              'message' => "Entity not found"
            ));
        }

        $entity->monetized = false;
        $entity->save();

        return Factory::response(array());
    }
}
