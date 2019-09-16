<?php
/**
 * Briefdescription.
 *
 * @author emi
 */

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Entities\User;

class BriefdescriptionDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return (bool) $user->briefdescription;
    }
}
