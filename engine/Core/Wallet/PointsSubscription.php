<?php
/**
 * Opspot Wallet Subscription Hook
 */
namespace Opspot\Core\Wallet;

use Opspot\Core;
use Opspot\Core\Payments;
use Opspot\Core\Payments\HookInterface;
use Opspot\Helpers\Wallet as WalletHelper;
use Opspot\Entities;
use Opspot\Core\Blockchain\Transactions\Transaction;
use Opspot\Core\Di\Di;

class PointsSubscription implements HookInterface
{
    private $rate = 0.001;

    public function onCharged($subscription)
    {
        /*error_log("[webhook]:: " .  print_r($subscription, true));
        $db = new Core\Data\Call('user_index_to_guid');

        //find the customer
        $user_guids = $db->getRow("subscription:" . $subscription->getId());
        $user = Entities\Factory::build($user_guids[0]);

        error_log("[webhook]:: got onCharge");*/

        if ($subscription->getPlanId() == 'points') {
            $user = $subscription->getCustomer()->getUser();
            $transaction = new Transaction(); 
            $transaction
                ->setUserGuid($user->guid)
                ->setWalletAddress('offchain')
                ->setTimestamp(time())
                ->setTx('cc:' . $subscription->getId())
                ->setAmount(($subscription->getPrice()) * 1.1 * 10 ** 18)
                ->setContract('offchain:points')
                ->setCompleted(true);

            Di::_()->get('Blockchain\Transactions\Repository')
                ->add($transaction);
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
    }

    public function onPayoutPaid($payout, $customer, $account)
    {
    }
}
