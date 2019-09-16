<?php

namespace Spec\Opspot\Core\Blockchain\Contracts;

use Opspot\Core\Blockchain\Contracts\OpspotWithdraw;
use PhpSpec\ObjectBehavior;

class OpspotWithdrawSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('0x123');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OpspotWithdraw::class);
    }

    function it_should_get_the_abi()
    {
        $this->getABI()->shouldBeArray();
    }
}
