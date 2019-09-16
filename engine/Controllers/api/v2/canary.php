<?php
/**
 * API for enabling/disabling canary
 */
namespace Opspot\Controllers\api\v2;

use Opspot\Api\Factory;
use Opspot\Common\Cookie;
use Opspot\Core\Di\Di;
use Opspot\Core\Config;
use Opspot\Core\Session;
use Opspot\Interfaces;

class canary implements Interfaces\Api
{

    public function get($pages)
    {
        $user = Session::getLoggedInUser();
        if (!$user) {
            Factory::response([
                'status' => 'error',
                'message' => 'You are not logged in'
            ]);
        }
        return Factory::response([
            'enabled' => (bool) $user->isCanary(),
        ]);
    }

    public function post($pages)
    {
        return $this->delete($pages);
    }

    public function put($pages)
    {
        $user = Session::getLoggedInUser();
        $user->setCanary(true);
        $user->save();
      
        $message = 'Welcome to Canary! You will now receive the latest Opspot features before everyone else.'; 
        $dispatcher = Di::_()->get('EventsDispatcher');
        $dispatcher->trigger('notification', 'all', [
            'to' => [ $user->getGuid() ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 
                'message' => $message,
                'router_link' => '/canary'
            ],
            'message' => $message,
        ]); 
        
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $user = Session::getLoggedInUser();
        $user->setCanary(false);
        $user->save();
        return Factory::response([]);
    }
    
}


