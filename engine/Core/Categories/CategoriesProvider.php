<?php
/**
 * Opspot Categories Provider
 */

namespace Opspot\Core\Categories;

use Opspot\Core\Di\Provider;

class CategoriesProvider extends Provider
{
    public function register()
    {
        /**
         * Categories repository
         */
        $this->di->bind('Categories\Repository', function ($di) {
            return new Repository;
        }, ['useFactory'=>false]);
    }
}
