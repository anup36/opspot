<?php

namespace Spec\Opspot\Core\Comments\Delegates;

use Opspot\Core\Comments\Comment;
use Opspot\Core\Events\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CreateEventDispatcherSpec extends ObjectBehavior
{
    protected $eventsDispatcher;

    function let(
        Dispatcher $eventsDispatcher
    ) {
        $this->beConstructedWith($eventsDispatcher);

        $this->eventsDispatcher = $eventsDispatcher;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Delegates\CreateEventDispatcher');
    }

    // EventsDispatcher cannot be tested yet
}
