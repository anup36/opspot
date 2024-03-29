<?php
/**
 * Cache Delegate
 */
namespace Opspot\Core\Subscriptions\Delegates;

use Opspot\Core\Di\Di;

class CacheDelegate
{

    /** @var Cache $cache */
    private $cache;

    public function __construct($cache = null)
    {
        $this->cache = $cache ?: Di::_()->get('Cache');
    }

    /**
     * Cache subscription
     */
    public function cache($subscription)
    {
        $this->cache->set("{$subscription->getSubscriberGuid()}:isSubscribed:{$subscription->getPublisherGuid()}", $subscription->isActive());
        $this->cache->set("{$subscription->getPublisherGuid()}:isSubscriber:{$subscription->getSubscriberGuid()}", $subscription->isActive());

        $this->cache->destroy("friends:{$subscription->getSubscriberGuid()}");
        $this->cache->destroy("friendsof:{$subscription->getPublisherGuid()}");

        $this->cache->destroy("{$subscription->getSubscriberGuid()}:friendscount");
        $this->cache->destroy("{$subscription->getPublisherGuid()}:friendsofcount");
    }
}
