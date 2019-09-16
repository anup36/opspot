<?php


namespace Opspot\Core\Email\Batches;


use Opspot\Core\Analytics\Timestamps;
use Opspot\Core\Di\Di;
use Opspot\Core\Email\Campaigns\WhenNotifications;
use Opspot\Core\Email\EmailSubscribersIterator;
use Opspot\Core\Notification\Counters;
use Opspot\Core\Notification\Repository;
use Opspot\Entities\Notification;

class Notifications implements EmailBatchInterface
{
    /** @var Repository */
    protected $repository;

    /** @var Counters $counters */
    protected $counters;

    protected $offset = '';

    public function __construct(
        $notificationRepository= null,
        $counters = null
    ) {
        $this->repository = $notificationRepository ?: Di::_()->get('Notification\Repository');
        $this->counters = $counters ?: new Counters();
    }

    public function setOffset($offset)
    {
        $this->offset = isset($offset) ?: '';
        return $this;
    }

    public function run()
    {

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('when')
            ->setTopic('unread_notifications')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $this->counters->setUser($user->guid);
            $count = $this->counters->getCount();

            if ($count >= 0) {

                // get latest notifications
                try {
                    /** @var Notification[] $notifications */
                    $notifications = $this->repository->getAll(['limit' => $count])['notifications'];
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    continue;
                }

                $today = Timestamps::get(['day'])['day'];

                $i = 0;

                //count all notifications created today
                while ($i < count($notifications) && $today <= $notifications[$i]->getTimeCreated()) {
                    $i++;
                }

                if ($i >= 1) {
                    $campaign = new WhenNotifications();

                    $campaign->setUser($user)
                        ->setAmount($i)
                        ->send();
                }
            }
        }
    }
}
