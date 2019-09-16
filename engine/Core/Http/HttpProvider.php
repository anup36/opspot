<?php
/**
 * Opspot HTTP Provider
 */

namespace Opspot\Core\Http;

use Opspot\Core\Di\Provider;

class HttpProvider extends Provider
{
    public function register()
    {
        /**
         * HTTP bindings
         */
        $this->di->bind('Http', function ($di) {
            return new Curl\Client();
        }, ['useFactory'=>true]);

        $this->di->bind('Http\Json', function ($di) {
            return new Curl\Json\Client();
        }, ['useFactory'=>true]);

        $this->di->bind('Http\JsonRpc', function ($di) {
            return new Curl\JsonRpc\Client();
        }, ['useFactory'=>true]);
    }
}
