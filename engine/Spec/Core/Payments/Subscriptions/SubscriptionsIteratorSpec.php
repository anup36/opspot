<?php

namespace Spec\Opspot\Core\Payments\Subscriptions;

use Opspot\Core\Di\Di;
use Opspot\Core\Payments\Subscriptions\Manager;
use Opspot\Core\Payments\Subscriptions\Repository;
use Opspot\Core\Payments\Subscriptions\Subscription;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubscriptionsIteratorSpec extends ObjectBehavior
{
    protected $repository;

    function let(
        Repository $repository
    )
    {
        $this->repository = $repository;

        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Payments\Subscriptions\SubscriptionsIterator');
    }

    function it_should_get()
    {
        $timestamp = strtotime('2000-01-01T12:00:00+00:00');
        $subscriptions = [ 
            (new Subscription())
                ->setId('1'),
            (new Subscription())
                ->setId('2'),
        ];

        $this->setFrom($timestamp)
            ->setPlanId('spec')
            ->setPaymentMethod('tokens');

        $this->repository->getList([
            'plan_id' => 'spec',
            'payment_method' => 'tokens',
            'limit' => 2000,
            'status' => 'active',
            'next_billing' => $timestamp
        ])
            ->shouldBeCalled()
            ->willReturn($subscriptions);

        $this->rewind();

        $this->current()
            ->shouldReturn($subscriptions[0]);

        $this->next();

        $this->current()
            ->shouldReturn($subscriptions[1]);
    }

}
