<?php

/**
 * Opspot Post Notifications endpoint
 *
 * @author emi
 */

namespace Opspot\Controllers\api\v2\notifications;

use Opspot\Api\Factory;
use Opspot\Core\EntitiesBuilder;
use Opspot\Core\Notification\PostSubscriptions\Manager;
use Opspot\Core\Session;
use Opspot\Interfaces;

class follow implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $user = Session::getLoggedinUser();

        $manager = (new Manager());
        $manager
            ->setEntityGuid($pages[0])
            ->setUserGuid($user->guid);

        $postSubscription = $manager->get();

        return Factory::response([
            'postSubscription' => $postSubscription->export()
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        $user = Session::getLoggedinUser();
        $entityGuid = $pages[0];

        $manager = (new Manager());
        $manager
            ->setEntityGuid($entityGuid)
            ->setUserGuid($user->guid);

        $saved = $manager->follow(true);

        $entity = (new EntitiesBuilder())->single($entityGuid);
        if ($saved && $entity && $entity->entity_guid) {
            $manager
                ->setEntityGuid($entity->entity_guid)
                ->follow(true);
        }

        return Factory::response([
            'done' => $saved
        ]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        $user = Session::getLoggedinUser();
        $entityGuid = $pages[0];

        $manager = (new Manager());
        $manager
            ->setEntityGuid($entityGuid)
            ->setUserGuid($user->guid);

        $saved = $manager->unfollow();

        $entity = (new EntitiesBuilder())->single($entityGuid);
        if ($saved && $entity && $entity->entity_guid) {
            $manager
                ->setEntityGuid($entity->entity_guid)
                ->unfollow();
        }

        return Factory::response([
            'done' => $saved
        ]);
    }
}
