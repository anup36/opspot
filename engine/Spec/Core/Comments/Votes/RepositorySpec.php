<?php

namespace Spec\Opspot\Core\Comments\Votes;

use Opspot\Core\Comments\Comment;
use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\Data\Cassandra\Prepared\Custom;
use Opspot\Core\Votes\Vote;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    /** @var Client */
    protected $cql;

    function let(
        Client $cql
    ) {
        $this->beConstructedWith($cql);

        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Votes\Repository');
    }

    function it_should_add(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $cql = $prepared->build()['string'];

            return stripos($cql, 'update comments set votes_up = votes_up + ?') !== false;
        }));

        $this
            ->add($vote);
    }

    function it_should_delete(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this->cql->request(Argument::that(function (Custom $prepared) {
            $cql = $prepared->build()['string'];

            return stripos($cql, 'update comments set votes_down = votes_down - ?') !== false;
        }));

        $this
            ->delete($vote);
    }
}
