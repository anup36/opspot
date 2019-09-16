<?php

namespace Spec\Opspot\Core\Data;

use Opspot\Core\Data\cache\Redis;
use Opspot\Core\Data\Call;
use Opspot\Core\Data\Sessions;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SessionsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Sessions::class);
    }

}
