<?php

namespace Spec\Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Hashtags\User\Manager;
use Opspot\Core\Onboarding\Delegates\SuggestedHashtagsDelegate;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SuggestedHashtagsDelegateSpec extends ObjectBehavior
{
    protected $userHashtagsManager;

    function let(Manager $userHashtagsManager)
    {
        $this->beConstructedWith($userHashtagsManager);

        $this->userHashtagsManager = $userHashtagsManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SuggestedHashtagsDelegate::class);
    }

    function it_should_check_if_completed(User $user)
    {
        $this->userHashtagsManager->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->userHashtagsManager);

        $this->userHashtagsManager->get(['limit' => 1])
            ->shouldBeCalled()
            ->willReturn([[
                'selected' => true,
                'value' => 'phpspec'
            ]]);

        $this
            ->isCompleted($user)
            ->shouldReturn(true);
    }

    function it_should_check_if_not_completed(User $user)
    {
        $this->userHashtagsManager->setUser($user)
            ->shouldBeCalled()
            ->willReturn($this->userHashtagsManager);

        $this->userHashtagsManager->get(['limit' => 1])
            ->shouldBeCalled()
            ->willReturn([[
                'selected' => false,
                'value' => 'phpspec'
            ]]);

        $this
            ->isCompleted($user)
            ->shouldReturn(false);
    }
}
