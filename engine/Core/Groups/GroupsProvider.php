<?php
/**
 * Opspot Groups Provider
 */

namespace Opspot\Core\Groups;

use Opspot\Core\Di\Provider;
use Opspot\Core\Groups\AdminQueue;
use Opspot\Core\Groups\Feeds;

class GroupsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Groups\AdminQueue', function ($di) {
            return new AdminQueue();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Groups\Feeds', function ($di) {
            return new Feeds();
        }, [ 'useFactory'=> false ]);
    }
}
