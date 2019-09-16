<?php
/**
 * Opspot: Monetize
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Opspot\Controllers\api\v1;

use Opspot\Core;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class monetize implements Interfaces\Api
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

        if(!Core\Session::getLoggedinUser()->monetized && !Core\Session::isAdmin()){
            return Factory::response(array(
              'status' => 'error',
              'message' => "You don't have permission"
            ));
        }

        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(array(
              'status' => 'error',
              'message' => "Entity not found"
            ));
        }

        $entity->monetized = true;
        $entity->save();

        $db = new Core\Data\Call('entities_by_time');
        $e = new Core\Data\Call('entities');
        foreach ($db->getRow("activity:entitylink:$entity->guid") as $guid => $ts) {
            $e->insert($guid, ['monetized' => true]);
        }

        (new Core\Payments\Plans\PaywallReview())
            ->setEntityGuid($entity->guid)
            ->add();

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

        $db = new Core\Data\Call('entities_by_time');
        $e = new Core\Data\Call('entities');
        foreach ($db->getRow("activity:entitylink:$entity->guid") as $guid => $ts) {
            $e->insert($guid, ['monetized' => false]);
        }

        (new Core\Payments\Plans\PaywallReview())
            ->setEntityGuid($entity->guid)
            ->remove();

        return Factory::response(array());
    }
}
