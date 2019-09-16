<?php
/**
 * Opspot Trending Provider
 */

namespace Opspot\Core\Trending;

use Opspot\Core\Di\Provider;

class TrendingProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Trending\Repository', function ($di) {
            return new Repository();
        }, [ 'useFactory' => true ]);

        /**
         * Trending Services
         */
        $this->di->bind('Trending\Services\GoogleAnalytics', function ($di) {
            return new Services\GoogleAnalytics($di->get('Config'));
        }, [ 'useFactory' => true ]);
    }
}
