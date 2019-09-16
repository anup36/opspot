<?php

namespace Spec\Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Onboarding\Delegates\BriefdescriptionDelegate;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BriefdescriptionDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BriefdescriptionDelegate::class);
    }

    function it_should_check_if_completed(User $user)
    {
        $user->get('briefdescription')
            ->shouldBeCalled()
            ->willReturn('phpspec');

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    function it_should_check_if_not_completed(User $user)
    {
        $user->get('briefdescription')
            ->shouldBeCalled()
            ->willReturn('');

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }
}
