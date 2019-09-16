<?php

namespace Spec\Opspot\Core\Comments;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Comment');
    }
}
