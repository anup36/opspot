<?php

namespace Spec\Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Onboarding\Delegates\DisplayNameDelegate;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DisplayNameDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DisplayNameDelegate::class);
    }

    function it_should_check_if_completed(User $user)
    {
        $user->get('name')
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    function it_should_check_if_not_completed(User $user)
    {
        $user->get('name')
            ->shouldBeCalled()
            ->willReturn('');

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }
}
