<?php
namespace Opspot\Core\Config;

use Opspot\Core\Di\Provider;

/**
 * Opspot Config Providers
 */
class ConfigProvider extends Provider
{
    /**
     * Registers providers onto DI
     * @return null
     */
    public function register()
    {
        $this->di->bind('Config', function ($di) {
            return new Config();
        }, ['useFactory'=>true]);
    }
}
