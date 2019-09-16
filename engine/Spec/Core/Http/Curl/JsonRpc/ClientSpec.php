<?php

namespace Spec\Opspot\Core\Http\Curl\JsonRpc;

use Opspot\Core\Http\Curl\JsonRpc\Client;
use PhpSpec\ObjectBehavior;
use Opspot\Core\Http\Curl\CurlWrapper;

class ClientSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
        $this->getCurl()->shouldHaveType(CurlWrapper::class);
    }
}
