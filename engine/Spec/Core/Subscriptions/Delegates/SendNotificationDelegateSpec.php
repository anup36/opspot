<?php

namespace Spec\Opspot\Core\Subscriptions\Delegates;

use Opspot\Core\Subscriptions\Delegates\SendNotificationDelegate;
use Opspot\Core\Subscriptions\Subscription;
use Opspot\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SendNotificationDelegateSpec extends ObjectBehavior
{
    private $eventsDispatcher;

    function let(EventsDispatcher $eventsDispatcher)
    {
        $this->beConstructedWith($eventsDispatcher);
        $this->eventsDispatcher = $eventsDispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SendNotificationDelegate::class);
    }

    function it_should_send_a_notification()
    {
        $subscription = new Subscription;
        $subscription->setSubscriberGuid(123)
            ->setPublisherGuid(456)
            ->setActive(true);

        $this->eventsDispatcher->trigger('notification', 'all', [
            'to' => [ 456 ],
            'entity' => 123,
            'notification_view' => 'friends',
            'from' => 123,
            'params' => [],
        ])
            ->shouldBeCalled();

        $this->send($subscription);
    }

}
