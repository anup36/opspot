<?php

namespace Spec\Opspot\Core\Payments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SaleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Payments\Sale');
    }
}
