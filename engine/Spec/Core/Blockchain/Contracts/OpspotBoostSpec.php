<?php

namespace Spec\Opspot\Core\Blockchain\Contracts;

use Opspot\Core\Blockchain\Contracts\OpspotBoost;
use PhpSpec\ObjectBehavior;

class OpspotBoostSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('0x123');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OpspotBoost::class);
    }

    function it_should_get_the_abi()
    {
        $this->getABI()->shouldBeArray();
    }
}
