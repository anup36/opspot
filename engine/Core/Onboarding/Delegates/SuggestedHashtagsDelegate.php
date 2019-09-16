<?php
/**
 * SuggestedHashtags.
 *
 * @author emi
 */

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Core\Hashtags\User\Manager;
use Opspot\Entities\User;

class SuggestedHashtagsDelegate implements OnboardingDelegate
{
    /** @var Manager */
    protected $userHashtagsManager;

    /**
     * SuggestedHashtags constructor.
     * @param null $userHashtagsManager
     */
    public function __construct($userHashtagsManager = null)
    {
        $this->userHashtagsManager = $userHashtagsManager ?: new Manager();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        $userHashtags = $this->userHashtagsManager
            ->setUser($user)
            ->get(['limit' => 1]);

        return $userHashtags && count($userHashtags) > 0 && $userHashtags[0]['selected'];
    }
}
