<?php

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Entities\User;

/**
 * OnboardingDelegate
 *
 * @author edgebal
 */

interface OnboardingDelegate
{
    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user);
}
