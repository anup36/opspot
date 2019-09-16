<?php

namespace Spec\Opspot\Core\Blockchain\Purchase\Delegates;

use Opspot\Core\Blockchain\Purchase\Delegates\IssuedTokenNotification;
use Opspot\Core\Blockchain\Purchase\Purchase;
use Opspot\Core\Config;
use Opspot\Core\Events\EventsDispatcher;
use PhpSpec\ObjectBehavior;

class IssuedTokenNotificationSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IssuedTokenNotification::class);
    }

    function it_should_notify(Config $config, EventsDispatcher $dispatcher, Purchase $purchase)
    {
        $this->beConstructedWith($config, $dispatcher);

        $purchase->getIssuedAmount()
            ->shouldBeCalled()
            ->willReturn(10000000000000000000);

        $purchase->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('1234');

        $dispatcher->trigger('notification', 'all', [
            'to' => ['1234'],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [
                'message' => 'Your purchase of 10 Tokens has now been issued.',
                'router_link' => '/token'
            ],
            'message' => 'Your purchase of 10 Tokens has now been issued.'
        ])
            ->shouldBeCalled();

        $this->notify($purchase);
    }
}
