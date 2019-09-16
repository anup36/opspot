<?php

/**
 * Placeholder endpoint for boost
 *
 * @author emi
 */

namespace Opspot\Controllers\api\v1;

use Opspot\Api\Factory;
use Opspot\Controllers;
use Opspot\Interfaces;

class boost implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return (new Controllers\api\v2\boost())->get($pages);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([
            'status' => 'error',
            'message' => 'Boost v1 is no longer supported'
        ]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([
            'status' => 'error',
            'message' => 'Boost v1 is no longer supported'
        ]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([
            'status' => 'error',
            'message' => 'Boost v1 is no longer supported'
        ]);
    }
}
