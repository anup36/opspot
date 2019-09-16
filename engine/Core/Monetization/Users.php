<?php
namespace Opspot\Core\Monetization;

use Opspot\Core;
use Opspot\Entities;
use Opspot\Core\Di\Di;
use Opspot\Core\Payments\Stripe\Stripe;
use Opspot\Core\Payments\Transfers\Transfer;

class Users
{
    protected $db;

    public function __construct()
    {
    }

    public function setUser($user)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $this->user = $user;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTransactions($limit = 12, $offset = '')
    {
        if (!$this->user) {
            throw new \Exception('No user');
        }

        $manager = Di::_()->get('Monetization\Manager');
        $collection = $manager->get([
            'user_guid' => $this->user,
            'limit' => 12,
            'offset' => $offset,
            'order' => 'DESC',
        ]);

        if (!$collection) {
            return [];
        }

        $items = [];

        foreach ($collection as $item) {
            $item['amount'] = (float) ($item['amount'] / 100);
            $items[] = $item;
        }

        return $items;
    }
}
