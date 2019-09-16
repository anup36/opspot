<?php
/**
 * Email module.
 */

namespace Opspot\Core\Email;

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

        $events = new Events();
        $events->register();
    }
}
