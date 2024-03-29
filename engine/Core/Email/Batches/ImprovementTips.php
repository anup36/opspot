<?php

namespace Opspot\Core\Email\Batches;

use Opspot\Core\Email\Campaigns\WithImprovementTips;
use Opspot\Core\Email\EmailSubscribersIterator;

class ImprovementTips implements EmailBatchInterface
{
    protected $offset;

    /**
     * @param string $offset
     * @return ImprovementTips
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function run()
    {
        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('with')
            ->setTopic('channel_improvement_tips')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $campaign = new WithImprovementTips();

            $campaign
                ->setUser($user)
                ->send();
        }
    }
}
