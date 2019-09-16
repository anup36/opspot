<?php

namespace Spec\Opspot\Core\Hashtags\User;

use Opspot\Core\Hashtags\User\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }
}
