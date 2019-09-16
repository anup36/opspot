<?php

/**
 * Site Contributions Overview
 *
 * @author emi
 */

namespace Opspot\Controllers\api\v2\blockchain\contributions;

use Opspot\Core\Session;
use Opspot\Interfaces;
use Opspot\Api\Factory;
use Opspot\Core\Rewards\Contributions;

class overview implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $overview = new Contributions\Overview();
        $overview
            ->setUser(Session::getLoggedinUser())
            ->calculate();

        $response = [
            'nextPayout' => $overview->getNextPayout(),
            'currentReward' => $overview->getCurrentReward(),
            'yourContribution' => $overview->getYourContribution(),
            'totalNetworkContribution' => $overview->getTotalNetworkContribution(),
            'yourShare' => $overview->getYourShare(),
        ];
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
