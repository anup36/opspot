<?php
/**
 * Opspot OAuth 2 pollyfil
 */

namespace Opspot\Controllers\oauth2;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class token extends core\page implements Interfaces\page
{

    public function get($pages)
    {

    }

    public function post($pages)
    {
        header("HTTP/1.1 401 Unauthorized");
        return Factory::response([
            'status' => 'error',
            'message' => 'Please upgrade your app',
        ]);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }

}
