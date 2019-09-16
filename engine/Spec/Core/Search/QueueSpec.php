<?php

namespace Spec\Opspot\Core\Search;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QueueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Search\Queue');
    }

    // TODO: Remove static from Queue Build
}
