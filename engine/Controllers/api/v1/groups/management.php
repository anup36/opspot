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
use Opspot\Entities\User;

use Opspot\Exceptions\GroupOperationException;

class management implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        /** @var \Opspot\Entities\Group $group */
        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();

        if (!$group || !$group->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($pages[1]) || !$pages[1] || !ctype_alnum($pages[1])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid user'
            ]);
        }

        $member = new User($pages[1]);

        if (!$member || !$member->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $management = (new Core\Groups\Management())
          ->setGroup($group)
          ->setActor($actor);

        if (!$group->isOwner($actor->guid)) {
            return Factory::response([
                'done' => false,
                'status' => 'error',
                'error' => 'Only owners can make these changes',
            ]);
        }

        try {
            if (isset($pages[2]) && $pages[2] == 'moderator') {
                $granted = $management->grantModerator($member);
            } else {
                $granted = $management->grantOwner($member);
            }
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        if ($granted && isset($pages[2]) && $pages[2] === 'owner') {
            if ((string)$actor->guid !== (string)$group->owner_guid) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Only a group owner can pass ownership'
                ]);
            }

            $saved = false;
            $group->setOwnerObj($member);
            try {
                $saved = $group->save();
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
            return Factory::response(['done' => $saved]);
        }

        return Factory::response([
            'done' => $granted
        ]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();

        if (!$group || !$group->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($pages[1]) || !$pages[1] || !ctype_alnum($pages[1])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid user'
            ]);
        }

        $member = new User($pages[1]);

        if (!$member || !$member->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $management = (new Core\Groups\Management())
          ->setGroup($group)
          ->setActor($actor);

        if (!$group->isOwner($actor->guid)) {
            return Factory::response([
                'done' => false,
                'status' => 'error',
                'error' => 'Only owners can make these changes',
            ]);
        }

        try {
            if (isset($pages[2]) && $pages[2] == 'moderator') {
                $revoked = $management->revokeModerator($member);
            } else {
                $revoked = $management->revokeOwner($member);
            }
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'done' => $revoked
        ]);
    }
}
