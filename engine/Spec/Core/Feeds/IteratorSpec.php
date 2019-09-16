<?php

namespace Spec\Opspot\Core\Feeds;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IteratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Feeds\Iterator');
    }
}
