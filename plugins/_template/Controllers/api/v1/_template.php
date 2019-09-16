<?php
/**
 * {{plugin.name}}
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Plugin\{{plugin.name}}\Controllers\api\v1;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Helpers;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class {{plugin.name}} implements Interfaces\Api
{

    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }


}
