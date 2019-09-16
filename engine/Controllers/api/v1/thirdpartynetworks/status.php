<?php
namespace Opspot\Controllers\api\v1\thirdpartynetworks;
/**
 * Opspot TPN General endpoint
 */

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class status implements Interfaces\Api
{

    /**
     * Get request
     * @param array $pages
     */
    public function get($pages)
    {
        return Factory::response([
            'thirdpartynetworks' => Core\Di\Di::_()->get('ThirdPartyNetworks\Manager')->status()
        ]);
    }

    /**
     * Post request
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Put request
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * Delete request
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
