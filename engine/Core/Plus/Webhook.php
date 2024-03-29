<?php
/**
 * Opspot Wallet Subscription Hook
 */
namespace Opspot\Core\Plus;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Payments\HookInterface;
use Opspot\Helpers\Wallet as WalletHelper;
use Opspot\Core\Blockchain\Transactions\Transaction;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        if ($subscription->getPlanId() == 'plus') {
            
            //save the plus flag to the user
            $user = $subscription->getCustomer()->getUser();
            $user->setPlusExpires(strtotime('+30 days', time()));
            $user->save();
            
            /** @var Core\Payments\Manager $manager */
            $manager = Di::_()->get('Payments\Manager');
            $manager
                ->setType('plus')
                ->setUserGuid($user->guid)
                ->setTimeCreated(time())
                ->create([
                    'subscription_id' => $subscription->getId(),
                    'payment_method' => 'money',
                    'amount' => $subscription->getPrice(),
                    'description' => 'Plus',
                    'status' => 'paid'
                ]);
        }
    }

    public function onActive($subscription)
    {
        error_log("[webhook]:: gotOnActive");
    }

    public function onExpired($subscription)
    {
    }

    public function onOverdue($subscription)
    {
    }

    public function onCanceled($subscription)
    {
        error_log("[webhook]:: canceled");
        /*$user = $subscription->getCustomer()->getUser();

        $plus = new Subscription();
        $plus->setUser($user);
        $plus->cancel();

        $user->plus = 0;
        $user->save();*/
    }

    public function onPayoutPaid($payout, $customer, $account)
    {
    }
}
