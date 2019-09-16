<?php

namespace Spec\Opspot\Core\Faq;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Faq\Manager');
    }

    function it_should_read_the_csv()
    {
        $this->get()->shouldBeArray();
        $this->get()->shouldHaveCount(17);
    }

}
