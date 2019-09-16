<?php

namespace Spec\Opspot\Core\Faq;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategoryFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Faq\CategoryFactory');
    }
}
