<?php

namespace Opspot\Controllers\api\v2\newsfeed;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Entities;
use Opspot\Entities\Activity;
use Opspot\Interfaces;

class pin implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send an Activity GUID']);
        }

        /** @var Activity $activity */
        $activity = Entities\Factory::build($pages[0]);

        $user = Core\Session::getLoggedinUser();

        if ($activity->container_guid != $user->guid) {
            $group = Entities\Factory::build($activity->container_guid);
            if ($group->isModerator($user) || $group->isOwner($user)) {
                $group->addPinned($activity->guid);
                $group->save();
            } else {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'You do not not have permission to pin to this group',
                ]);
            }
        } else {
            $user->addPinned($activity->guid);
            $user->save();
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'You must send an Activity GUID']);
        }
        /** @var Activity $activity */
        $activity = Entities\Factory::build($pages[0]);
        $user = Core\Session::getLoggedinUser();

        if ($activity->container_guid != $user->guid) {
            $group = Entities\Factory::build($activity->container_guid);
            if ($group->isModerator($user) || $group->isOwner($user)) {
                $group->removePinned($activity->guid);
                $group->save();
            } else {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'You do not not have permission to pin to this group',
                ]);
            }
        } else {
            $user->removePinned($activity->guid);
            $user->save();
        }

        return Factory::response([]);
    }

}
