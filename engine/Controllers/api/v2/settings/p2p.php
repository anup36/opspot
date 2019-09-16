<?php

namespace Opspot\Controllers\Api\v2\settings;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Email\EmailSubscription;
use Opspot\Entities\User;
use Opspot\Interfaces;

class p2p implements Interfaces\Api
{
    public function get($pages)
    {
        $user = Core\Session::getLoggedInUser();

        return Factory::response([
            'p2p_media_enabled' => $user->isP2PMediaEnabled()
        ]);
    }

    public function post($pages)
    {
        $user = Core\Session::getLoggedInUser();

        $user->setP2PMediaEnabled(true);
        $user->save();

        return Factory::response(['done' => true]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $user = Core\Session::getLoggedInUser();

        $user->setP2PMediaEnabled(false);
        $user->save();

        return Factory::response(['done' => true]);
    }

}
