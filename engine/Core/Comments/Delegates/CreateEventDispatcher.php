<?php

/**
 * Description
 *
 * @author emi
 */

namespace Opspot\Core\Comments\Delegates;

use Opspot\Core\Comments\Comment;
use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;

class CreateEventDispatcher
{
    /** @var Dispatcher */
    protected $eventsDispatcher;

    public function __construct($eventsDispatcher = null)
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function dispatch(Comment $comment)
    {
        $this->eventsDispatcher->trigger('create', 'elgg/event/comment', $comment);
        $this->eventsDispatcher->trigger('save', 'comment', [ 'entity' => $comment ]);
    }
}
