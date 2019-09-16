<?php

namespace Spec\Opspot\Core\Helpdesk\Question\Votes;

use Opspot\Core\Helpdesk\Question\Votes\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }
}
