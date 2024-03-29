<?php

namespace Spec\Opspot\Core\Groups;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Entities\User;
use Opspot\Core\Security\ACL;
use Opspot\Core\Data\Call;
use Opspot\Core\Data\Cassandra\Thrift\Relationships;
use Opspot\Entities\Group as GroupEntity;

class InvitationsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Groups\Invitations');
    }

    public function it_should_get_invitations(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($db);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:invited', Argument::any())->shouldBeCalled()->willReturn([11, 12, 13]);

        $group->getGuid()->willReturn(50);

        $this->setGroup($group);
        $this->getInvitations([ 'hydrate' => false ])->shouldReturn([11, 12, 13]);
    }

    public function it_should_check_invited_users_in_batch(GroupEntity $group, Relationships $db)
    {
        $this->beConstructedWith($db);

        $group->getGuid()->willReturn(50);

        $db->setGuid(50)->shouldBeCalled();
        $db->get('group:invited', Argument::any())->shouldBeCalled()->willReturn([11, 12, 13]);

        $this->setGroup($group);
        $this->isInvitedBatch([11, 12, 14])->shouldReturn([11 => true, 12 => true, 14 => false]);
    }

    public function it_should_check_if_its_invited(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->isInvited($user)->shouldReturn(true);
    }

    public function it_should_invite_to_a_public_group(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->invite($user, [ 'notify' => false ])->shouldReturn(true);
    }

    public function it_should_not_invite_to_a_private_group(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(false);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->create('group:invited', 50)->shouldNotBeCalled();

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(false);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Opspot\Exceptions\GroupOperationException')->duringInvite($user, [ 'notify' => false ]);
    }

    public function it_should_invite_to_a_private_group_by_an_owner(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(false);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->invite($user, [ 'notify' => false ])->shouldReturn(true);
    }

    public function it_should_uninvite(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([ '1' => 123456 ]);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->uninvite($user)->shouldReturn(true);
    }

    public function it_should_not_uninvite_a_non_subscriber(GroupEntity $group, Relationships $db, User $user, User $actor, ACL $acl, Call $friendsDB)
    {
        $this->beConstructedWith($db, $acl, $friendsDB);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);
        $actor->isAdmin()->willReturn(false);

        $group->getGuid()->willReturn(50);
        $group->isPublic()->willReturn(true);
        $group->isMember($actor)->willReturn(true);
        $group->isMember($user)->willReturn(false);

        $db->remove('group:invited', 50)->shouldNotBeCalled();

        $friendsDB->getRow(2, Argument::any())->shouldBeCalled()->willReturn([]);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->shouldThrow('\Opspot\Exceptions\GroupOperationException')->duringUninvite($user);
    }

    public function it_should_accept(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $group->join($user, [ 'force' => true ])->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($user);
        $this->accept()->shouldReturn(true);
    }

    public function it_should_fail_to_accept_if_not_invited(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(false);
        $db->remove('group:invited', 50)->shouldNotBeCalled();

        $group->join($user, [ 'force' => true ])->shouldNotBeCalled();

        $this->setGroup($group);
        $this->setActor($user);
        $this->shouldThrow('\Opspot\Exceptions\GroupOperationException')->duringAccept();
    }

    public function it_should_decline(GroupEntity $group, Relationships $db, User $user)
    {
        $this->beConstructedWith($db);

        $user->get('guid')->willReturn(1);
        $group->getGuid()->willReturn(50);

        $db->setGuid(1)->shouldBeCalled();
        $db->check('group:invited', 50)->shouldBeCalled()->willReturn(true);
        $db->remove('group:invited', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($user);
        $this->decline()->shouldReturn(true);
    }
}
