<?php
/**
 * Opspot Admin: Spam
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Opspot\Controllers\api\v1\admin;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class spam implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Get's an entities' spam state
     * @param array $pages
     */
    public function get($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $isSpam = false;
        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'getSpam')) {
            $isSpam = $entity->getSpam();
        } else if (method_exists($entity, 'getFlag')) {
            $isSpam = $entity->getFlag('spam');
        }

        return Factory::response([
            'spam' => $isSpam
        ]);
    }

    /**
     * POST (not used)
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Sets an entity as spam
     * @param array $pages
     */
    public function put($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'setSpam')) {
            $entity->setSpam(true);
        } else if (method_exists($entity, 'setFlag')) {
            $entity->setFlag('spam', true);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot set this entity as spam'
            ]);
        }

        if ($entity->entity_guid) {
            $child = Entities\Factory::build($entity->entity_guid);

            if (method_exists($child, 'setSpam')) {
                $child->setSpam(true);
            } else if (method_exists($child, 'setFlag')) {
                $child->setFlag('spam', true);
            }

            $child->save();
        }

        $success = $entity->save();

        if ($success) {
            return Factory::response([
                'spam' => true
            ]);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error setting as spam'
            ]);
        }
    }

    /**
     * Removes an entity's spam flag
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'setSpam')) {
            $entity->setSpam(false);
        } else if (method_exists($entity, 'setFlag')) {
            $entity->setFlag('spam', false);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot unset this entity as spam'
            ]);
        }

        if ($entity->entity_guid) {
            $child = Entities\Factory::build($entity->entity_guid);

            if (method_exists($child, 'setSpam')) {
                $child->setSpam(false);
            } else if (method_exists($child, 'setFlag')) {
                $child->setFlag('spam', false);
            }

            $child->save();
        }

        $success = $entity->save();

        if ($success) {
            return Factory::response([
                'spam' => false
            ]);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error setting as spam'
            ]);
        }
    }
}
