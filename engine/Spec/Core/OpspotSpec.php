<?php

namespace Spec\Opspot\Core;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OpspotSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Opspot');
    }
}
