<?php
namespace Opspot\Helpers\Campaigns;

use Opspot\Core;
use Opspot\Core\Guid;
use Opspot\Common\Cookie;

class Referrals
{
    /**
     * Registers a cookie for the referral step
     * @param  string  $username
     * @return null
     */
    public static function register($username)
    {
        if(!isset($_COOKIE['referrer'])) {
            $cookie = new Cookie();
            $cookie
                ->setName('referrer')
                ->setValue($username)
                ->setExpire(time() + (60 * 60 * 24)) //valid for 24 hours
                ->setPath('/')
                ->create();

            $_COOKIE['referrer'] = $username;
        }
    }
}
