<?php
/**
 * VideoChat module
 */
namespace Opspot\Core\VideoChat;

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
