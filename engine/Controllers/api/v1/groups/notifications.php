<?php
/**
 * Opspot Group API
 * Notification-related endpoints
 */
namespace Opspot\Controllers\api\v1\groups;

use Opspot\Core;
use Opspot\Core\Session;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Entities\Factory as EntitiesFactory;
use Opspot\Exceptions\GroupOperationException;

class notifications implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$group->isMember($user)) {
            return Factory::response([
                'is:muted' => false
            ]);
        }

        $notifications = (new Core\Groups\Notifications)
          ->setGroup($group);

        return Factory::response([
            'is:muted' => $notifications->isMuted($user)
        ]);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$group->isMember($user)) {
            return Factory::response([]);
        }

        $notifications = (new Core\Groups\Notifications)
          ->setGroup($group);

        try {
            switch ($pages[1]) {
              case 'mute':
                  $notifications->mute($user);
                  return Factory::response([
                      'is:muted' => true
                  ]);
                  break;
              case 'unmute':
                  $notifications->unmute($user);
                  return Factory::response([
                      'is:muted' => false
                  ]);
            }
        } catch (GroupOperationException $e) {
            return Factory::response([
                'is:muted' => false,
                'error' => $e->getMessage()
            ]);
        }

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
