<?php

/**
 * Opspot Subscriptions Provider
 *
 * @author emi / mark
 */

namespace Opspot\Core\Payments\Subscriptions;

use Opspot\Core\Di\Provider;

class SubscriptionsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Payments\Subscriptions\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory' => false ]);

        $this->di->bind('Payments\Subscriptions\Iterator', function ($di) {
            return new SubscriptionsIterator();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Payments\Subscriptions\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory' => true ]);
    }
}
