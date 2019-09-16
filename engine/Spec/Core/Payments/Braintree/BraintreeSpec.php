<?php

namespace Spec\Opspot\Core\Payments\Braintree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Config\Config;

use Braintree_Configuration;

class BraintreeSpec extends ObjectBehavior
{


    function it_is_initializable(Braintree_Configuration $btConfig, Config $config)
    {
        $this->beConstructedWith($btConfig, $config);
        $this->shouldHaveType('Opspot\Core\Payments\Braintree\Braintree');
    }
}
