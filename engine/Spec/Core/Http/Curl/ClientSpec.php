<?php

namespace Spec\Opspot\Core\Http\Curl;

use Opspot\Core\Http\Curl\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }
}
