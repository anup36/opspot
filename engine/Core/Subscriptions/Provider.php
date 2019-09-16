<?php
/**
 * Opspot Subscriptions Provider
 */

namespace Opspot\Core\Subscriptions;

use Opspot\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Subscriptions\Manager', function ($di) {
            return new Manager();
        }, [ 'useFactory'=>false ]);
    }
}
