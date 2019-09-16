<?php

namespace Spec\Opspot\Core\Email\Verify;

use Opspot\Core\Email\Verify\Manager;
use Opspot\Core\Email\Verify\Services\Kickbox;
use Opspot\Core\Security\SpamBlocks\Manager as SpamBlocksManager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $service;
    private $spamBlocksManager;

    function let(Kickbox $service, SpamBlocksManager $spamBlocksManager)
    {
        $this->beConstructedWith($service, $spamBlocksManager);
        $this->service = $service;
        $this->spamBlocksManager = $spamBlocksManager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_verify_and_hit_spam_blocks_manager_first()
    {
        $this->spamBlocksManager->isSpam(Argument::that(function($model) {
            return $model->getKey() == 'email_hash'
                && $model->getValue() == hash('sha256', 'hello@ops.doesntexist.com');
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->verify('hello@ops.doesntexist.com')
            ->shouldReturn(false);
    }

    function it_should_check_3rd_party_service()
    {
        $this->spamBlocksManager->isSpam(Argument::that(function($model) {
            return $model->getKey() == 'email_hash'
                && $model->getValue() == hash('sha256', 'hello@ops.doesntexist.com');
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        // Call 3rd party service
        $this->service->verify('hello@ops.doesntexist.com')
            ->shouldBeCalled()
            ->willReturn(false);

        // Add spam block to avoid hitting multiple times
        $this->spamBlocksManager->add(Argument::that(function($model) {
            return $model->getKey() == 'email_hash'
                && $model->getValue() == hash('sha256', 'hello@ops.doesntexist.com');
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->verify('hello@ops.doesntexist.com')
            ->shouldReturn(false);
    }

    function it_should_return_true_if_not_spam()
    {
        $this->spamBlocksManager->isSpam(Argument::that(function($model) {
            return $model->getKey() == 'email_hash'
                && $model->getValue() == hash('sha256', 'hello@ops.doesntexist.com');
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        // Call 3rd party service
        $this->service->verify('hello@ops.doesntexist.com')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->verify('hello@ops.doesntexist.com')
            ->shouldReturn(true);
    }

}
