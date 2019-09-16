<?php

namespace Spec\Opspot\Core\Pages;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MenuSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Pages\Menu');
    }
}
