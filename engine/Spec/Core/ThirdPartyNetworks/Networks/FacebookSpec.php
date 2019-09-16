<?php

namespace Spec\Opspot\Core\ThirdPartyNetworks\Networks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FacebookSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\ThirdPartyNetworks\Networks\Facebook');
    }
}
