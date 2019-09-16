<?php

namespace Spec\Opspot\Core\Search;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DocumentsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Search\Documents');
    }
}
