<?php
/**
 * Opspot Categories API
 *
 * @version 1
 * @author Mark Harding
 */

namespace Opspot\Controllers\api\v1;

use Opspot\Api\Factory;
use Opspot\Core\Config;
use Opspot\Interfaces;

class categories implements Interfaces\Api
{
    /**
     * Returns the categories
     * @param array $pages
     *
     */
    public function get($pages)
    {
        $response = [
            'categories' => Config::_()->get('categories')
        ];
        return Factory::response($response);
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
