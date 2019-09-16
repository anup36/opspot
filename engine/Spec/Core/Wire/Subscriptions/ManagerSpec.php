<?php

namespace Spec\Opspot\Core\Wire\Subscriptions;

use Opspot\Core\Di\Di;
use Opspot\Core\Payments\Subscriptions\Manager;
use Opspot\Core\Payments\Subscriptions\Repository;
use Opspot\Core\Wire\Exceptions\WalletNotSetupException;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Wire\Subscriptions\Manager');
    }

    function it_should_create_a_subscription(
        Manager $manager
    )
    {
        $this->beConstructedWith($manager);


        $sender = new User();
        $sender->guid = 123;
        $receiver = new User();
        $receiver->guid = 456;

        $manager->setSubscription(Argument::that(function($subscription) {
            return $subscription->getUser()->guid == 123
                && $subscription->getEntity()->guid == 456
                && $subscription->getAmount() == 5;
            }))
            ->willReturn(123);
        $manager->create()->shouldBeCalled();
        
        $this->setAmount(5)
            ->setSender($sender)
            ->setReceiver($receiver);
        $this->create()->shouldBeString();
    }

}
