<?php

/**
 * Opspot Features Provider
 *
 * @author emi
 */

namespace Opspot\Core\Features;

use Opspot\Core\Di\Provider;

class FeaturesProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Features', function ($di) {
            return new Manager();
        }, [ 'useFactory'=> true ]);
    }
}
