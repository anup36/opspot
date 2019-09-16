<?php


namespace Opspot\Core\Queue\Runners;


use Opspot\Core\Di\Di;
use Opspot\Core\Email\EmailSubscription;
use Opspot\Core\Email\Repository;
use Opspot\Core\Queue;
use Opspot\Core\Queue\Interfaces\QueueRunner;
use Opspot\Entities\User;

class Registered implements QueueRunner
{
    public function run()
    {
        $config = Di::_()->get('Config');
        $subscriptions = $config->get('default_email_subscriptions');
        /** @var Repository $repository */
        $repository = Di::_()->get('Email\Repository');

        $client = Queue\Client::Build();
        $client->setQueue("Registered")
            ->receive(function ($data) use ($subscriptions, $repository) {
                $data = $data->getData();
                $user_guid = $data['user_guid'];

                //subscribe to opspot channel
                $subscriber = new User($user_guid); 
                $subscriber->subscribe('100000000000000519');
    

                echo "[registered]: User registered $user_guid\n";

                foreach ($subscriptions as $subscription) {
                    $sub = array_merge($subscription, ['userGuid' => $user_guid]);
                    $repository->add(new EmailSubscription($sub));
                }

                echo "[registered]: subscribed {$user_guid} to default email notifications \n";
            });
        $this->run();
    }
}
