<?php
/**
 * Opspot Settings
 *
 * @author emi
 */

namespace Opspot\Controllers\api\v1\opspot;

use Opspot;
use Opspot\Core;
use Opspot\Interfaces;
use Opspot\Api\Factory;

class config implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $opspot = [
            "cdn_url" => Opspot\Core\Config::_()->get('cdn_url') ?: Opspot\Core\Config::_()->cdn_url,
            "site_url" => Opspot\Core\Config::_()->get('site_url') ?: Opspot\Core\Config::_()->site_url,
            "socket_server" => Opspot\Core\Config::_()->get('sockets-server-uri') ?: 'ha-socket-io-us-east-1.ops.doesntexist.com:3030',
            "thirdpartynetworks" => Opspot\Core\Di\Di::_()->get('ThirdPartyNetworks\Manager')->availableNetworks(),
            "categories" => Opspot\Core\Config::_()->get('categories') ?: [],
            "stripe_key" => Opspot\Core\Config::_()->get('payments')['stripe']['public_key'],
            "recaptchaKey" => Opspot\Core\Config::_()->get('google')['recaptcha']['site_key'],
            "max_video_length" => (Core\Session::getLoggedInUser() && Core\Session::getLoggedInUser()->isPlus())
                ? Opspot\Core\Config::_()->get('max_video_length_plus') 
                : Opspot\Core\Config::_()->get('max_video_length'),
            "features" => (object) (Opspot\Core\Config::_()->get('features') ?: []),
            "blockchain" => (object) Opspot\Core\Di\Di::_()->get('Blockchain\Manager')->getPublicSettings(),
            "plus" => Opspot\Core\Config::_()->get('plus'),
        ];

        return Factory::response($opspot);
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
