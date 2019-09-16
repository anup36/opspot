<?php
/**
 * Subscription hook interface
 */
namespace Opspot\Core\Payments\Subscriptions;

use Opspot\Core\Payments;

interface SubscriptionsHookInterface extends Payments\HookInterface
{
    public function onCharged($subscription);

    public function onActive($subscription);

    public function onExpired($subscription);

    public function onOverdue($subscription);

    public function onCanceled($subscription);
}
