<?php
/**
 * Experiments module
 */
namespace Opspot\Core\Experiments;

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
