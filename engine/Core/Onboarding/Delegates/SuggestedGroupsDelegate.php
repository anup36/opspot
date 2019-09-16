<?php
/**
 * SuggestedGroups.
 *
 * @author emi
 */

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Entities\User;

class SuggestedGroupsDelegate implements OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        return count($user->getGroupMembership() ?: []) > 0;
    }
}
