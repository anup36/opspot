<?php

namespace Opspot\Core\Email\Campaigns;

use Opspot\Core\Email\Message;
use Opspot\Core\Email\EmailSubscription;
use Opspot\Core\Email\Manager;
use Opspot\Entities\User;
use Opspot\Traits\MagicAttributes;

abstract class EmailCampaign
{
    use MagicAttributes;
    protected $campaign;
    protected $topic;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Message
     */
    abstract public function send();

    /**
     * Determines whether or not we can send an email to a user.
     * Requires setting the user object and setting the manager.
     */
    public function canSend()
    {
        if (
            !$this->user
            || !$this->user instanceof \Opspot\Entities\User
            || $this->user->enabled != 'yes'
        ) {
            return false;
        }

        $emailSubscription = (new EmailSubscription())
            ->setUserGuid($this->user->guid)
            ->setCampaign($this->campaign)
            ->setTopic($this->topic)
            ->setValue(true);

        if (!$this->manager || !$this->manager instanceof \Opspot\Core\Email\Manager || !$this->manager->isSubscribed($emailSubscription)) {
            return false;
        }

        return true;
    }
}
