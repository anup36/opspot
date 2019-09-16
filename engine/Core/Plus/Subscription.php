<?php

namespace Opspot\Core\Plus;

use Opspot\Core\Di\Di;
use Opspot\Core\Payments;
use Opspot\Core\Payments\Subscriptions;
use Opspot\Entities\User;
use Opspot\Core\Payments\Subscriptions\Manager;
use Opspot\Core\Payments\Subscriptions\Repository;


class Subscription
{

    private $stripe;
    private $repo;
    protected $user;
    /** @var Manager $subscriptionsManager */
    protected $subscriptionsManager;
    /** @var Repository $subscriptionsRepository */
    protected $subscriptionsRepository;

    public function __construct(
        $stripe = null,
        $subscriptionsManager = null,
        $subscriptionsRepository = null
    )
    {
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Payments\Subscriptions\Manager');
        $this->subscriptionsRepository = $subscriptionsRepository ?: Di::_()->get('Payments\Subscriptions\Repository');
    }

    /**
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $subscription = $this->getSubscription();

        if (!$subscription) {
            return false;
        }

        return $subscription->getStatus() == 'active';
    }

    /**
     * @param $subscription
     * @return $this
     * @throws \Exception
     */
    public function create($subscription)
    {
        $subscription->setInterval('monthly')
            ->setAmount(5);

        $this->subscriptionsManager
            ->setSubscription($subscription)
            ->create();

        return $this;
    }

    public function cancel()
    {
        $subscription = $this->getSubscription();

        if ($this->user->referrer){
            $referrer = new User($this->user->referrer);
            $subscription->setMerchant($referrer->getMerchant());
        }

        try{
            $this->stripe->cancelSubscription($subscription);
            $this->subscriptionsManager
                ->setSubscription($subscription)
                ->cancel();
        } catch (\Exception $e) { }

        return $this;
    }

    /**
     * @return array
     */
    public function getSubscription()
    {
        $subscriptions = $this->subscriptionsRepository->getList([
            'plan_id' => 'plus',
            'payment_method' => 'money',
            'user_guid' => $this->user->guid
        ]);

        return $subscriptions[0];
    }

}
