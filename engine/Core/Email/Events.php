<?php
/**
 * Email events.
 */

namespace Opspot\Core\Email;

use Opspot\Core\Events\Dispatcher;
use Opspot\Interfaces\ModuleInterface;
use Opspot\Core\Analytics\UserStates\UserActivityBuckets;
use Opspot\Core\Email\Campaigns\UserRetention\GoneCold;
use Opspot\Entities\User;

class Events implements ModuleInterface
{
    /**
     * OnInit.
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }

    public function register()
    {
        Dispatcher::register('user_state_change', 'all', function ($opts) {
            error_log('user_state_change all');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CASUAL, function ($opts) {
            error_log('user_state_change casual');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_COLD, function ($opts) {
            error_log('user_state_change cold');
            $params = $opts->getParameters();
            $user = new User($params['user_guid']);
            $campaign = (new GoneCold())
                ->setUser($user);
            $campaign->send();
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CORE, function ($opts) {
            error_log('user_state_change core');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CURIOUS, function ($opts) {
            error_log('user_state_change curious');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_NEW, function ($opts) {
            error_log('user_state_change new');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_RESURRECTED, function ($opts) {
            error_log('user_state_change resurrected');
        });
    }
}
