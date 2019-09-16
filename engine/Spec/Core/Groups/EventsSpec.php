<?php

namespace Spec\Opspot\Core\Groups;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Groups;
use Opspot\Entities\Activity;
use Opspot\Entities\Group;


class EventsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Groups\Events');
    }

    public function deletes_from_admin_queue_on_activity_delete_event(Groups\AdminQueue $adminQueue, Group $group, Activity $activity)
    {
        // TODO: finish?
        $activity->getGuid()->willReturn(1111);
        $group->getGuid()->willReturn(2222);
        $activity->getContainerGUID()->willReturn(2222);
        $activity->getContainerEntitiy()->willReturn($group);

        Dispatcher::register();
        Dispatcher::trigger('delete', 'activity', [ 'entity' => $activity ]);

        $adminQueue->delete(Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);
    }
}
