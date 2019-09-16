<?php

namespace Spec\Opspot\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Payments\Subscriptions;
use Opspot\Core\Payments\HookInterface;

class HooksSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Payments\Hooks');
    }

    function it_should_call_a_function_of_all_hooks()
    {
        $this->callMe(['foo'=>'bar'])->shouldReturn($this);
    }

    function it_should_register_a_hook(HookInterface $hook)
    {
        $this->register($hook)->shouldReturn($this);
    }

}
