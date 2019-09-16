<?php

namespace Spec\Opspot\Core\Votes;

use Opspot\Core\Events\EventsDispatcher;
use Opspot\Core\Di\Di;
use Opspot\Core\Security\ACL;
use Opspot\Core\Votes\Counters;
use Opspot\Core\Votes\Indexes;
use Opspot\Core\Votes\Vote;
use Opspot\Entities\Activity;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var ACL */
    protected $acl;
    /** @var Counters */
    protected $counters;
    /** @var Indexes */
    protected $indexes;
    /** @var EventsDispatcher */
    protected $dispatcher;

    function let(
        ACL $acl,
        Counters $counters,
        Indexes $indexes,
    EventsDispatcher $dispatcher
    )
    {
        $this->acl = $acl;
        $this->counters = $counters;
        $this->indexes = $indexes;
        $this->dispatcher = $dispatcher;

        $this->beConstructedWith($counters, $indexes, $acl, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Votes\Manager');
    }

    function it_should_cast(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->acl->interact($entity, $user, 'voteup')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->counters->update($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cast($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_cast_and_send_a_vote_event(
        Vote $vote,
        Activity $entity,
        User $user
    ) {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->acl->interact($entity, $user, 'voteup')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->dispatcher->trigger('vote:action:cast', Argument::any(), ['vote' => $vote], null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->dispatcher->trigger('vote', 'up', ['vote' => $vote])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cast($vote, [ 'events' => true ])
            ->shouldReturn(true);
    }

    function it_should_throw_during_insert_if_cannot_interact(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->acl->interact($entity, $user, 'voteup')
            ->shouldBeCalled()
            ->willReturn(false);

        $this->counters->update($vote)
            ->shouldNotBeCalled();

        $this->indexes->insert($vote)
            ->shouldNotBeCalled();

        $this->shouldThrow(new \Exception('Actor cannot interact with entity'))
            ->duringCast($vote, [ 'events' => false ]);
    }

    function it_should_cancel(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->counters->update($vote, -1)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->remove($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cancel($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_cancel_and_send_a_vote_cancel_event(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');


        $this->dispatcher->trigger('vote:action:cancel', Argument::any(), ['vote' => $vote], null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->dispatcher->trigger('vote:cancel', Argument::any(), ['vote' => $vote], null)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->cancel($vote, [ 'events' => true ])
            ->shouldReturn(true);
    }

    function it_should_be_true_if_has(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->has($vote)
            ->shouldReturn(true);
    }

    function it_should_be_false_if_not_has(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->has($vote)
            ->shouldReturn(false);
    }

    function it_should_toggle_on(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);
        $vote->getDirection()->willReturn('up');

        $this->acl->interact($entity, $user, 'voteup')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->counters->update($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->insert($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->toggle($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }

    function it_should_toggle_off(
        Vote $vote,
        Activity $entity,
        User $user
    )
    {
        $vote->getEntity()->willReturn($entity);
        $vote->getActor()->willReturn($user);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->exists($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->counters->update($vote, -1)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->indexes->remove($vote)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->toggle($vote, [ 'events' => false ])
            ->shouldReturn(true);
    }
}
