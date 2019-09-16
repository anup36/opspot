<?php

namespace Spec\Opspot\Core\Security\SpamBlocks;

use Opspot\Core\Security\SpamBlocks\IPHash;
use Opspot\Core\Security\SpamBlocks\Manager;
use Opspot\Core\Security\SpamBlocks\SpamBlock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IPHashSpec extends ObjectBehavior
{

    private $manager;

    function let(Manager $manager)
    {
        $this->beConstructedWith($manager);
        $this->manager = $manager;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IPHash::class);
    }

    function it_should_return_true_if_spam_ip()
    {
        $this->manager->isSpam(Argument::that(function($model) {
                return $model->getKey('ip_hash')
                    && $model->getValue('10.0.1.1');
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->isValid('10.0.1.1')
            ->shouldReturn(false);
    }

    function it_should_return_false_if_not_spam_ip()
    {
        $this->manager->isSpam(Argument::that(function($model) {
                return $model->getKey('ip_hash')
                    && $model->getValue('10.0.1.1');
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->isValid('10.0.1.1')
            ->shouldReturn(true);
    }

}
