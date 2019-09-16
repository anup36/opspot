<?php

/**
 * Blockchain Boost preparation
 *
 * @author Nicolas Ronchi
 */

namespace Opspot\Controllers\api\v2\channels;

use Opspot\Api\Factory;
use Opspot\Core\Di\Di;
use Opspot\Interfaces;
use Opspot\Core\Data\Cassandra\Prepared;
use Opspot\Entities;
use Opspot\Core;

class founders implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

        $db = new \Opspot\Core\Data\Call('entities_by_time');
        $founders = $db->getRow('user:founders', ['limit' => 25, 'offset' => $offset]);
        if (!$founders) {
            return Factory::response([]);
        }
        $options = ['guids'=>array_values($founders)];
        $users = Core\Entities::get($options);
        
        $response['users'] = factory::exportable($users);
        $response['load-next'] = (string) end($users)->guid;
        $response['load-previous'] = (string) key($users)->guid;

        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
