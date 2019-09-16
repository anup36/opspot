<?php
/**
 * Payment service interface
 */
namespace Opspot\Core\Payments\Subscriptions;

use Opspot\Core\Payments\Customer;
use Opspot\Core\Payments\PaymentMethod;
use Opspot\Core\Payments\Subscriptions\Subscription;

interface SubscriptionPaymentServiceInterface
{
    public function createCustomer(Customer $customer);

    public function createPaymentMethod(PaymentMethod $payment_method);

    public function createSubscription(Subscription $subscription);

    //public function getSubscription(Subscription $subscription);
}
