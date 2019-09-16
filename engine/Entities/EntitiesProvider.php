<?php

namespace Opspot\Entities;

use Opspot\Core\Di\Provider;
use Opspot\Core\Entities;
use Opspot\Core\EntitiesBuilder;

class EntitiesProvider extends Provider
{
    /**
     * Registers providers onto DI
     * @return void
     */
    public function register()
    {
        $this->di->bind('Entities', function ($di) {
            return new Entities();
        }, ['useFactory' => true]);
        $this->di->bind('EntitiesBuilder', function ($di) {
            return new EntitiesBuilder();
        }, ['useFactory' => true]);
        $this->di->bind('Entities\Factory', function ($di) {
            return new EntitiesFactory();
        }, ['useFactory' => true]);
    }
}
