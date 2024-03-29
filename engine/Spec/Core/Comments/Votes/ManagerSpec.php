<?php

namespace Spec\Opspot\Core\Comments\Votes;

use Opspot\Core\Comments\Comment;
use Opspot\Core\Comments\Legacy\Repository as LegacyCommentsRepository;
use Opspot\Core\Comments\Votes\Repository;
use Opspot\Core\Votes\Vote;
use Opspot\Entities\Entity;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var LegacyCommentsRepository */
    protected $legacyRepository;

    function let(
        Repository $repository,
        LegacyCommentsRepository $legacyRepository
    ) {
        $this->beConstructedWith($repository, $legacyRepository);

        $this->repository = $repository;
        $this->legacyRepository = $legacyRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Votes\Manager');
    }

    function it_has_vote_up(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesUp()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesDown()
            ->shouldNotBeCalled();

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(999);

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(true);
    }

    function it_does_not_have_vote_up(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesUp()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesDown()
            ->shouldNotBeCalled();

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(999);

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1003);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('up');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(false);
    }

    function it_has_vote_down(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesDown()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesUp()
            ->shouldNotBeCalled();

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(999);

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1000);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(true);
    }

    function it_does_not_have_vote_down(
        Vote $vote,
        Comment $comment,
        User $actor
    )
    {
        $comment->getVotesDown()
            ->shouldBeCalled()
            ->willReturn([ 1000, 1001 ]);

        $comment->getVotesUp()
            ->shouldNotBeCalled();

        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(999);

        $actor->get('guid')
            ->shouldBeCalled()
            ->willReturn(1003);

        $vote->getDirection()
            ->shouldBeCalled()
            ->willReturn('down');

        $vote->getEntity()
            ->shouldBeCalled()
            ->willReturn($comment);

        $vote->getActor()
            ->shouldBeCalled()
            ->willReturn($actor);

        $this
            ->setVote($vote)
            ->has()
            ->shouldReturn(false);
    }

    function it_should_cast(
        Vote $vote,
        Comment $comment
    ) {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $vote->getEntity()->willReturn($comment);

        $this->legacyRepository->isLegacy('5000');

        $this->repository->add($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setVote($vote)
            ->cast()
            ->shouldReturn(true);
    }

    function it_should_cancel(
        Vote $vote,
        Comment $comment
    ) {
        $comment->getEntityGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $vote->getEntity()->willReturn($comment);

        $this->legacyRepository->isLegacy('5000');

        $this->repository->delete($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setVote($vote)
            ->cancel()
            ->shouldReturn(true);
    }
}
