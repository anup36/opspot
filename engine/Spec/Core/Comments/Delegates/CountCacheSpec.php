<?php

namespace Spec\Opspot\Core\Comments\Delegates;

use Opspot\Core\Comments\Comment;
use Opspot\Core\Data\cache\abstractCacher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountCacheSpec extends ObjectBehavior
{
    /** @var abstractCacher */
    protected $cache;

    function let(
        abstractCacher $cache
    )
    {
        $this->beConstructedWith($cache);

        $this->cache = $cache;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Delegates\CountCache');
    }

    function it_should_destroy(
        Comment $comment
    )
    {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $this->cache->destroy('comments:count:5000')
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->destroy($comment)
            ->shouldNotThrow();
    }
}
