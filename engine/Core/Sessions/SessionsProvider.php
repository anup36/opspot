<?php
/**
 * Opspot Sessions Provider
 */
namespace Opspot\Core\Sessions;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Di\Provider;

class SessionsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Sessions\Manager', function ($di) {
            return new Manager;
        }, ['useFactory'=>true]);
    }

}
