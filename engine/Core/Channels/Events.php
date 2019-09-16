<?php
/**
 * Opspot Channels Events Listeners
 *
 * @author Mark
 */

namespace Opspot\Core\Comments;

use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Events\Event;
use Opspot\Core\Votes\Vote;

class Events
{
    /** @var Manager */
    protected $manager;

    /** @var Dispatcher */
    protected $eventsDispatcher;

    /**
     * Events constructor.
     * @param Manager $manager
     * @param Dispatcher $eventsDispatcher
     */
    public function __construct($manager = null, $eventsDispatcher = null)
    {
        $this->manager = $manager ?: new Manager();
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function register()
    {
        // Entity save
        $this->eventsDispatcher->register('entity:delete', 'user', function (Event $event) {
            $user = $event->getParameters()['entity'];

            $event->setResponse($this->manager->delete($comment));
        });
    }

}
