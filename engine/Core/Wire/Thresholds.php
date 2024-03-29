<?php
namespace Opspot\Core\Wire;

use Opspot\Core;
use Opspot\Core\Di\Di;
use Opspot\Core\Payments;
use Opspot\Core\Util\BigNumber;
use Opspot\Entities\User;
use Opspot\Helpers\MagicAttributes;

class Thresholds
{
    /**
     * Check if the entity can be shown to the passed user
     * @param User $user
     * @param $entity
     * @return bool
     * @throws \Exception
     */
    public function isAllowed($user, $entity)
    {
        if (!is_object($entity) || !(MagicAttributes::getterExists($entity, 'getWireThreshold') || method_exists($entity, 'getWireThreshold'))) {
            throw new \Exception('Entity cannot be paywalled');
        }

	    if ($user && ($user->guid == $entity->getOwnerEntity()->guid || $user->isAdmin())) {
            return true;
        }

        $isPaywall = false;
        if (method_exists($entity, 'isPaywall') && $entity->isPaywall()) {
            $isPaywall = true;
        } elseif (method_exists($entity, 'getFlag') && $entity->getFlag('paywall')) {
            $isPaywall = true;
        }

        $threshold = $entity->getWireThreshold();

        if (!$threshold && $isPaywall) {
            $threshold = [
                'type' => 'money',
                'min' => $entity->getOwnerEntity()->getMerchant()['exclusive']['amount']
            ];
        }

        //make sure legacy posts can work
        if ($isPaywall) {

            $amount = 0;

            /** @var Sums $sums */
            $sums = Di::_()->get('Wire\Sums');
            $sums->setReceiver($entity->getOwnerGUID())
                ->setSender($user->guid)
                ->setFrom((new \DateTime('midnight'))->modify("-30 days")->getTimestamp());

            $amount = $sums->getSent();

            $minThreshold = $threshold['min'];

            if ($threshold['type'] === 'tokens') {
                $minThreshold = BigNumber::toPlain($threshold['min'], 18);
            }

            $allowed = BigNumber::_($amount)->sub($minThreshold)->gte(0);

            if ($allowed) {
                return true;
            }

            //Plus hack
            if ($entity->owner_guid == '730071191229833224') {
                $plus = (new Core\Plus\Subscription())->setUser($user);

                if ($plus->isActive()) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }
}
