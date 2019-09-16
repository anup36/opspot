<?php

namespace Spec\Opspot\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuidSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Guid');
    }

    public function it_should_return_a_guid()
    {
        $this::build()->shouldBeString();
    }
}
