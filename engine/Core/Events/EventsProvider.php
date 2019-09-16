<?php
/**
 * Opspot Events Provider
 */

namespace Opspot\Core\Events;

use Opspot\Core\Di\Provider;

class EventsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('EventsDispatcher', function ($di) {
            return new EventsDispatcher();
        }, ['useFactory' => true]);
    }
}
