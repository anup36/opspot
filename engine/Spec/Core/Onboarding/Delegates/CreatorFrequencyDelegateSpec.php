<?php

namespace Spec\Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Onboarding\Delegates\CreatorFrequencyDelegate;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreatorFrequencyDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CreatorFrequencyDelegate::class);
    }

    function it_should_check_if_completed(User $user)
    {
        $user->getCreatorFrequency()
            ->shouldBeCalled()
            ->willReturn('rarely');

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    function it_should_check_if_not_completed(User $user)
    {
        $user->getCreatorFrequency()
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }
}
