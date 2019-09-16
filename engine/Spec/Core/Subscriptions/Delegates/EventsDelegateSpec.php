<?php

namespace Spec\Opspot\Core\Subscriptions\Delegates;

use Opspot\Core\Subscriptions\Delegates\EventsDelegate;
use Opspot\Core\Subscriptions\Subscription;
use Opspot\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsDelegateSpec extends ObjectBehavior
{

    private $eventsDispatcher;

    function let(EventsDispatcher $eventsDispatcher)
    {
        $this->beConstructedWith($eventsDispatcher);
        $this->eventsDispatcher = $eventsDispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventsDelegate::class);
    }

    function it_should_trigger_an_active_subscription_event()
    {
        $subscription = new Subscription;
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(true);

        $this->eventsDispatcher->trigger('subscribe', 'all', [
            'user_guid' => 123,
            'to_guid' => 456,
            'subscription' => $subscription,
        ])
            ->shouldBeCalled();

        $this->trigger($subscription);
    }

    function it_should_trigger_an_unsubscribe_event()
    {
        $subscription = new Subscription;
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(false);

        $this->eventsDispatcher->trigger('unsubscribe', 'all', [
            'user_guid' => 123,
            'to_guid' => 456,
            'subscription' => $subscription,
        ])
            ->shouldBeCalled();

        $this->trigger($subscription);
    }

}
