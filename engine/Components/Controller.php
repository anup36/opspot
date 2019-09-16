<?php
namespace Opspot\Components;

use Opspot\Core\Di\Di;

/**
 * API Controller
 * @todo Move to Opspot\Api namespace (to reflect Cli structure).
 * @todo Create a BaseController class (to be used on Api, Cli, etc) with core DI operations.
 * @todo Ensure this class is used EVERYWHERE on Opspot\Controllers\api
 */
class Controller {
    protected $di;
    protected $config;

    public function __construct()
    {
        $this->di = Di::_();
        $this->config = $this->di->get('Config');
    }
}
