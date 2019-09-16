<?php

namespace Spec\Opspot\Core\VideoChat;

use Opspot\Core\Data\cache\abstractCacher;
use Opspot\Core\Data\cache\Redis;
use Opspot\Core\VideoChat\Manager;
use Opspot\Core\VideoChat\Leases\Manager as LeaseManager;
use Opspot\Core\VideoChat\Leases\VideoChatLease;
use Opspot\Entities\Group;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /** @var abstractCacher */
    private $cacher;

    private $leaseManager;

    function let(Redis $cacher, LeaseManager $leaseManager)
    {
        $this->cacher = $cacher;
        $this->leaseManager = $leaseManager;

        $this->beConstructedWith($cacher, $leaseManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_the_room_key(Group $group)
    {
        $lease = new VideoChatLease();
        $lease->setSecret('iamasecret');

        $this->leaseManager->get('groupname')
            ->shouldBeCalled()
            ->willReturn($lease);

        $group->getGuid()
            ->shouldBeCalled()
            ->willReturn('groupname');

        $this->setEntity($group);
        $this->getRoomKey()->shouldBe('iamasecret');
    }

    function it_should_create_a_lease_if_not_found(Group $group, User $user)
    {
        $this->leaseManager->get('groupname')
            ->shouldBeCalled()
            ->willReturn(false);

        $this->leaseManager->add(Argument::that(function($lease) {
                return $lease->getKey() == 'groupname';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $group->getGuid()
            ->shouldBeCalled()
            ->willReturn('groupname');

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn(123);

        $this->setEntity($group);
        $this->setUser($user);
        $this->getRoomKey()->shouldContain('opspot-groupname-');
    }

    /*function it_should_refresh_a_rooms_ttl()
    {
        $key = 'opspot-groupnam-asdasd123';
        $this->cacher->get($key)
            ->shouldBeCalled()
            ->willReturn(1);

        $this->cacher->set($key, true, 7200)
            ->shouldBeCalled();

        $this->refreshTTL($key);
    }

    function it_shouldnt_refresh_ttl_if_the_room_does_not_exist()
    {
        $key = 'opspot-groupnam-asdasd123';
        $this->cacher->get($key)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->refreshTTL($key);
    }*/
}
