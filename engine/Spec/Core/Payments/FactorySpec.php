<?php

namespace Spec\Opspot\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Opspot\Core\Config;

class FactorySpec extends ObjectBehavior
{
    public function let()
    {

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Payments\Factory');
    }

    public function it_should_build_a_service()
    {
        $this::build('braintree', ['gateway'=>'merchants'])->shouldImplement('Opspot\Core\Payments\PaymentServiceInterface');
    }

    public function it_should_throw_an_exception_if_service_doesnt_exist()
    {
        $this->shouldThrow('\Exception')->during('build', ['foobar']);
    }
}
