<?php


namespace Opspot\Controllers\api\v2\blockchain;

use Opspot\Api\Factory;
use Opspot\Core;
use Opspot\Core\Rewards\Join;
use Opspot\Core\Session;
use Opspot\Interfaces;

class rewards implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();
        $response = [];

        switch ($pages[0]) {
            case 'verify':
                if (!isset($_POST['number'])) {
                    return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
                }
                $number = $_POST['number'];
                $resend = $_POST['retry'];

                try {
                    $join = new Join();
                    $join
                        ->setUser(Session::getLoggedInUser())
                        ->setNumber($number);

                    if (!$resend) {
                        $secret = $join->verify();
                    } else {
                        $secret = $join->resendCode();
                    }

                    $response['secret'] = $secret;
                } catch (\Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage(),
                    ];
                }
                break;
            case 'confirm':
                if (!isset($_POST['number'])) {
                    return Factory::response(['status' => 'error', 'message' => 'phone field is required']);
                }

                $number = $_POST['number'];

                if (!isset($_POST['code'])) {
                    return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                }
                $code = $_POST['code'];

                if (!isset($_POST['secret'])) {
                    return Factory::response(['status' => 'error', 'message' => 'code field is required']);
                }
                $secret = $_POST['secret'];

                $user = Session::getLoggedInUser();

                try {
                    $join = new Join();
                    $join
                        ->setUser($user)
                        ->setNumber($number)
                        ->setCode($code)
                        ->setSecret($secret)
                        ->confirm();

                    $response['phone_number_hash'] = $user->getPhoneNumberHash();

                } catch (\Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Confirmation failed'
                    ];
                }
                break;
        }

        return Factory::response($response);
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
