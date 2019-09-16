<?php

/**
 * Opspot New Purchase Notification Delegate
 *
 * @author mark
 */

namespace Opspot\Core\Blockchain\Purchase\Delegates;

use Opspot\Core\Blockchain\Purchase\Purchase;
use Opspot\Core\Config;
use Opspot\Core\Di\Di;
use Opspot\Core\Events\Dispatcher;
use Opspot\Core\Events\EventsDispatcher;
use Opspot\Core\Util\BigNumber;

class NewPurchaseNotification
{
    /** @var Config */
    protected $config;

    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($config = null, $dispatcher = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function notify(Purchase $purchase)
    {
        $amount = (int) BigNumber::_($purchase->getRequestedAmount())->div(10 ** 18)->toString();

        $message = "Your purchase of $amount Tokens is being processed.";

        $this->dispatcher->trigger('notification', 'all', [
            'to' => [ $purchase->getUserGuid() ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 'message' => $message ],
            'message' => $message,
        ]);
    }
}
