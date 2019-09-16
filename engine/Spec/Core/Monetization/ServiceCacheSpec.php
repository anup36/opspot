<?php

namespace Spec\Opspot\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceCacheSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Monetization\ServiceCache');
    }
}
