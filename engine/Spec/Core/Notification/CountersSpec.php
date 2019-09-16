<?php

namespace Spec\Opspot\Core\Notification;

use Opspot\Core\Notification\Counters;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountersSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Counters::class);
    }

}
