<?php

/**
 * Blockchain Boost preparation
 *
 * @author emi
 */

namespace Opspot\Controllers\api\v2\boost;

use Opspot\Api\Factory;
use Opspot\Core\Boost\Checksum;
use Opspot\Core\Guid;
use Opspot\Interfaces;

class prepare implements Interfaces\Api
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0]) || !is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Specify an Entity GUID'
            ]);
        }

        $guid = Guid::build();
        $checksum = (new Checksum())
            ->setGuid($guid)
            ->setEntity($pages[0])
            ->generate();

        return Factory::response([
            'guid' => $guid,
            'checksum' => $checksum
        ]);
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
