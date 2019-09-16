<?php
/**
 * Opspot Monetization Affiliates
 *
 * @version 1
 * @author Mark Harding
 */
namespace Opspot\Controllers\api\v1\monetization;

use Opspot\Components\Controller;
use Opspot\Core;
use Opspot\Core\Config;
use Opspot\Helpers;
use Opspot\Entities;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Payments\Merchant;

class affiliates extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }


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
        $programs = Core\Di\Di::_()->get('Programs\Manager');
        $programs->setUser(Core\Session::getLoggedinUser())
          ->applyAndAccept('affiliate');
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
