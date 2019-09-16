<?php

namespace Spec\Opspot\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NormalizedEntitySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Entities\NormalizedEntity');
    }

    public function it_returns_a_guid()
    {
        $this->getGuid()->shouldBeNumeric();
    }
}
