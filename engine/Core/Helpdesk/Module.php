<?php
/**
 * Helpdesk module
 */
namespace Opspot\Core\Helpdesk;

use Opspot\Interfaces\ModuleInterface;

class Module implements ModuleInterface
{

    /**
     * OnInit
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }

}
