<?php

namespace Spec\Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Onboarding\Delegates\TokensVerificationDelegate;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokensVerificationDelegateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TokensVerificationDelegate::class);
    }

    function it_should_check_if_completed(User $user)
    {
        $user->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('0303456');

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    function it_should_check_if_not_completed(User $user)
    {
        $user->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn(null);

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }
}
