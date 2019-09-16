<?php

namespace Spec\Opspot\Core\Feeds\Legacy;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Feeds\Legacy\Repository');
    }
}
