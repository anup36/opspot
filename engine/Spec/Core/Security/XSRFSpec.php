<?php

namespace Spec\Opspot\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XSRFSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Security\XSRF');
    }
}
