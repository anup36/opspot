<?php
/**
 * Subscriptions module.
 */

namespace Opspot\Core\Subscriptions;

use Opspot\Interfaces\ModuleInterface;

class Module implements ModuleInterface
{
    /**
     * OnInit.
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }
}
