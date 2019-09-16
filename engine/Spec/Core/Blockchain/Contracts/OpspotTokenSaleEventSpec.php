<?php

namespace Spec\Opspot\Core\Blockchain\Contracts;

use Opspot\Core\Blockchain\Contracts\OpspotTokenSaleEvent;
use PhpSpec\ObjectBehavior;

class OpspotTokenSaleEventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('0x123');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OpspotTokenSaleEvent::class);
    }

    function it_should_get_the_abi()
    {
        $this->getABI()->shouldBeArray();
    }
}
