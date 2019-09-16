<?php
/**
 * {{plugin.name}}
 * @author {{plugin.author}}
 */

namespace Opspot\Plugin\{{plugin.name}};

use Opspot\Core;
use Opspot\Components;
use Opspot\Api;

class start extends Components\Plugin
{

    public function init()
    {

        //initialise our first api
        Api\Routes::add('v1/{{plugin.lc_name}}', 'Opspot\\Plugin\\{{plugin.name}}\\Controllers\\api\\v1\\{{plugin.name}}');

    }

}
