<?php

/**
 * Search Provider
 *
 * @author emi
 */

namespace Opspot\Core\Search;

use Opspot\Core\Di\Provider;
use Opspot\Core\Search\Hashtags\Manager;

class SearchProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Search\Queue', function ($di) {
            return new Queue();
        }, ['useFactory' => true]);

        $this->di->bind('Search\Index', function ($di) {
            return new Index();
        }, ['useFactory' => true]);

        $this->di->bind('Search\Search', function ($di) {
            return new Search();
        }, ['useFactory' => true]);

        $this->di->bind('Search\Mappings', function ($di) {
            return new Mappings\Factory();
        }, ['useFactory' => true]);

        $this->di->bind('Search\Provisioner', function ($di) {
            return new Provisioner();
        }, ['useFactory' => true]);

        $this->di->bind('Search\Hashtags\Manager', function ($di) {
            return new Manager();
        }, ['useFactory' => true]);
    }
}
