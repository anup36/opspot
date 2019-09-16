<?php
/**
 * Opspot Plugins Provider
 */

namespace Opspot\Core\Plugins;

use Opspot\Core\Di\Provider;

class PluginsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Plugins\Manager', function($di){
            return new Manager();
        }, ['useFactory'=>true]);
    }

}
