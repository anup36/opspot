<?php
/**
 * DisplayName.
 *
 * @author emi
 */

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Entities\User;

class DisplayNameDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return (bool) $user->name;
    }
}
