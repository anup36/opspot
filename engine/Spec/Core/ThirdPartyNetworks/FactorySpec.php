<?php

namespace Spec\Opspot\Core\ThirdPartyNetworks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\ThirdPartyNetworks\Factory');
    }
}
