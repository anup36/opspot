<?php

namespace Spec\Opspot\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImpressionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Analytics\Metrics\Impression');
    }
}
