<?php
/**
 * Opspot FAQ
 *
 * @version 2
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v2;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Entities;

class faq implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $response = [];
        $faq = Di::_()->get('Faq');

        $response['faq'] = $faq->get();

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
