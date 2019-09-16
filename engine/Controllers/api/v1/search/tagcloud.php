<?php
/**
 * Opspot Core Search Tag Cloud API
 *
 * @version 1
 * @author Mar Harding
 */
namespace Opspot\Controllers\api\v1\search;

use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Search;

class tagcloud implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        return Factory::response([
            'tags' => (new Search\Tagcloud())->get()
        ]);
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
