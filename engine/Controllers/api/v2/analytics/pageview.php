<?php


namespace Opspot\Controllers\api\v2\analytics;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Common\Cookie;
use Opspot\Entities;
use Opspot\Helpers\Counters;
use Opspot\Interfaces;

class pageview implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($_POST['url'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must provide a url'
            ]);
        }

        if(!isset($_COOKIE['mwa'])) {
            //@TODO make this more unique
            $id = uniqid(true);

            $cookie = new Cookie();
            $cookie
                ->setName('mwa')
                ->setValue($id)
                ->setExpire(time() + (60 * 60 * 24 * 30 * 12))
                ->setPath('/')
                ->create();

            $_COOKIE['mwa'] = $id;
        }

        $url = strtok($_POST['url'], '?');

        $event = new Core\Analytics\Metrics\Event();
        $event
            ->setType('action')
            ->setProduct('platform')
            ->setAction('pageview')
            ->setRouteUri($url)
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCookieId($_COOKIE['mwa']);

        if (isset($_POST['referrer']) && $_POST['referrer']) {
            $event->setReferrerUri((string) $_POST['referrer']);
        }

        if (Core\Session::isLoggedIn()) {
            $event->setLoggedIn(true);
        }
        $event->push();

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
