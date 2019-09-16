<?php
/**
 * Opspot Monetization
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

class revenue extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $user = Core\Session::getLoggedInUser();
        $stripe = Core\Di\Di::_()->get('StripePayments');

        $merchant = (new Merchant())->setId($user->getMerchant()['id']);

        if (!$merchant->getId()) {
          return Factory::response([
            'status' => 'error',
            'message' => 'User is not a merchant'
          ]);
        }

        $currency = "usd";
        $volume = $stripe->getGrossVolume($merchant);
        $payouts = $stripe->getTotalPayouts($merchant);
        $balance = 0;
        $balances = $stripe->getTotalBalance($merchant);
        foreach($balances as $c => $a){
            $currency = $c;
            $balance = $a;
        }

        switch($pages[0]){
            case 'overview':
                return Factory::response([
                    'currency' => $currency,
                    'total' => $volume,
                    'payouts' => $payouts,
                    'balance' => $balance
                ]);
                break;
        }

        return Factory::response([]);
    }


    public function post($pages)
    {

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
