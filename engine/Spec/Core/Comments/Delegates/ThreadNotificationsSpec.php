<?php

namespace Spec\Opspot\Core\Comments\Delegates;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ThreadNotificationsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Delegates\ThreadNotifications');
    }

    // EventsDispatcher cannot be tested yet
}
