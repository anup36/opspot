<?php
/**
 * Opspot Pages Provider
 */

namespace Opspot\Core\Pages;

use Opspot\Core;
use Opspot\Core\Data;
use Opspot\Core\Di\Provider;

class PagesProvider extends Provider
{
    public function register()
    {
        $this->di->bind('PagesManager', function ($di) {
            return new Manager(new Data\Call('entities_by_time'), new Data\Call('user_index_to_guid'));
        }, ['useFactory'=>true]);
    }
}
