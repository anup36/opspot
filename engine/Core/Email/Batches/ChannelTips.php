<?php

namespace Opspot\Core\Email\Batches;

use Opspot\Core\Di\Di;
use Opspot\Core\Email\Campaigns\WithChannelTips;
use Opspot\Core\Email\EmailSubscribersIterator;
use Opspot\Core\Entities;
use Opspot\Core\Trending\Repository;

class ChannelTips implements EmailBatchInterface
{
    protected $offset;

    /**
     * @param string $offset
     * @return ChannelTips
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
            ->setTopic('new_channels')
            ->setValue(true)
            ->setOffset($this->offset);

        $channels = $this->getNewChannels();
        if (!$channels || count($channels) === 0) {
            error_log('No trending channels were found!');
            return;
        }

        foreach ($iterator as $user) {
            $campaign = new WithChannelTips();

            $campaign
                ->setUser($user)
                ->setChannels($channels)
                ->send();
        }
    }

    private function getNewChannels()
    {
        /** @var Repository $repository */
        $repository = Di::_()->get('Trending\Repository');
        $result = $repository->getList(['type' => 'channels', 'limit' => 10, 'offset' => '']);
        if (!$result) {
            return [];
        }

        ksort($result['guids']);

        return Entities::get(['guids' => $result['guids']]);
    }
}
