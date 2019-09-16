<?php
namespace Opspot\Core\Boost;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Email\Campaigns;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Payments;
use Opspot\Core\Session;

/**
 * Opspot Payments Events
 */
class Events
{
    public function register()
    {
       Dispatcher::register('boost:completed', 'boost', function ($event) {
            $campaign = new Campaigns\WhenBoost();
            $params = $event->getParameters();
            $boost = $params['boost'];

            $campaign->setUser($boost->getOwner())
                ->setBoost($boost);

            $campaign->send();

            return $event->setResponse(true);
        });
    }
}
