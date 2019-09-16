<?php
/**
 * Avatar.
 *
 * @author emi
 */

namespace Opspot\Core\Onboarding\Delegates;

use Opspot\Entities\User;
use Opspot\Core\Config;
use Opspot\Core\Di\Di;

class AvatarDelegate implements OnboardingDelegate
{

    /** @var Config $config */
    private $config;

    /**
     * Manager constructor.
     * @param Config $config
     */
    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isCompleted(User $user)
    {
        $lastAvatarUpload = $user->getLastAvatarUpload();
        $timeCreated = $user->time_created;
        $timestamp = $this->config->get('onboarding_modal_timestamp') ?: 0;

        $isLegacyUser = $timeCreated <= $timestamp;

        return $isLegacyUser || $lastAvatarUpload > $timeCreated;
    }
}
