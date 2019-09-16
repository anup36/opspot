<?php

namespace Spec\Opspot\Core\Groups;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Security\ACL;
use Opspot\Entities\User;
use Opspot\Core\Data\Cassandra\Thrift\Relationships;
use Opspot\Entities\Group as GroupEntity;

class ManagementSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Groups\Management');
    }

    public function it_should_grant_owner(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->isMember($user)->shouldBeCalled()->willReturn(true);
        $group->pushOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor, null)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->grantOwner($user)->shouldReturn(true);
    }

    public function it_should_revoke_owner(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->getGuid()->willReturn(50);
        $group->removeOwnerGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:owner', 50)->shouldBeCalled()->willReturn(true);

        $acl->write($group, $actor, null)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->revokeOwner($user)->shouldReturn(true);
    }

    public function it_should_grant_moderator(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->isOwner($actor)->shouldBeCalled()->willReturn(true);
        $group->getGuid()->willReturn(50);
        $group->isMember($user)->shouldBeCalled()->willReturn(true);
        $group->pushModeratorGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->create('group:moderator', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->grantModerator($user)->shouldReturn(true);
    }

    public function it_should_revoke_moderator(GroupEntity $group, Relationships $db, ACL $acl, User $user, User $actor)
    {
        $this->beConstructedWith($db, $acl);

        $user->get('guid')->willReturn(1);

        $actor->get('guid')->willReturn(2);

        $group->isOwner($actor)->shouldBeCalled()->willReturn(true);
        $group->getGuid()->willReturn(50);
        $group->removeModeratorGuid(1)->shouldBeCalled();
        $group->save()->shouldBeCalled()->willReturn(true);

        $db->setGuid(1)->shouldBeCalled();
        $db->remove('group:moderator', 50)->shouldBeCalled()->willReturn(true);

        $this->setGroup($group);
        $this->setActor($actor);
        $this->revokeModerator($user)->shouldReturn(true);
    }
}
