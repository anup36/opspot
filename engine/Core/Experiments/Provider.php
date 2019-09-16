<?php
/**
 * Opspot Experiments Provider
 */

namespace Opspot\Core\Experiments;

use Opspot\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Experiments\Manager', function($di) {
            return new Manager;
        });
    }
}
