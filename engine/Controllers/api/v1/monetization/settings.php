<?php
/**
 * Opspot Monetization Settings
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

class settings extends Controller implements Interfaces\Api
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

        $merchant = $stripe->getMerchant($user->getMerchant()['id']);

        if (!$merchant->getId()) {
          return Factory::response([
            'status' => 'error',
            'message' => 'User is not a merchant'
          ]);
        }

        $account = [
          'id' => $merchant->getBankAccount()['id'],
          'bank' => $merchant->getBankAccount()['bank_name'],
          'last4' => $merchant->getBankAccount()['last4']
        ];

        return Factory::response([
          'bank' => $account,
          'country' => $merchant->getCountry()
        ]);
    }


    public function post($pages)
    {
        $user = Core\Session::getLoggedInUser();
        $stripe = Core\Di\Di::_()->get('StripePayments');

        $merchant = (new Merchant)->setId($user->getMerchant()['id']);

        if (!$merchant->getId()) {
          return Factory::response([
            'status' => 'error',
            'message' => 'User is not a merchant'
          ]);
        }

        $merchant->setCountry($_POST['country'])
          ->setAccountNumber($_POST['accountNumber'])
          ->setRoutingNumber($_POST['routingNumber']);

        try{
            $stripe->updateMerchantAccount($merchant);
            return Factory::response([
              'bank' => true
            ]);
        } catch(\Exception $e) {
            return Factory::response([
              'status' => 'error',
              'message' => $e->getMessage()
            ]);
        }
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
        Factory::isLoggedIn();

        $user = Core\Session::getLoggedInUser();
        $stripe = Core\Di\Di::_()->get('StripePayments');

        $merchant = (new Merchant)->setId($user->getMerchant()['id']);
        $response = [];

        switch ($pages[0]) {
            case "account":
                $success = $stripe->deleteMerchantAccount($merchant);
                if (!$success) {
                    $response['status'] = 'error';
                    $response['message'] = 'Could not delete monetization at this time';
                    break;
                }

                $user->setMerchant([]);
                $user->save();

                Core\Session::regenerate(true, $user);

                break;
        }
        return Factory::response($response);
    }
}
