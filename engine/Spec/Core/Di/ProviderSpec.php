<?php

namespace Spec\Opspot\Core\Di;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Di\Provider');
    }

    //function it_should_have_a_di_property()
    //{
    //    $this->di->shouldHaveType('Opspot\Core\Di\Di');
    //}
}
