<?php

namespace Spec\Opspot\Core\Email\Batches;

use Opspot\Core\Di\Di;
use Opspot\Core\Email\EmailSubscription;
use Opspot\Core\EntitiesBuilder;
use Opspot\Core\Notification\Repository;
use Opspot\Core\Notification\Counters;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotificationsSpec extends ObjectBehavior
{
    protected $notificationRepository;
    protected $emailRepository;

    /** @var EntitiesBuilder */
    protected $builder;

    function let(
        Repository $notificationRepository,
        \Opspot\Core\Email\Repository $emailRepository,
        EntitiesBuilder $builder,
        Counters $counters
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->emailRepository = $emailRepository;
        $this->builder = $builder;

        Di::_()->bind('Email\Repository', function ($di) use ($emailRepository) {
            return $emailRepository->getWrappedObject();
        });
        Di::_()->bind('EntitiesBuilder', function ($di) use ($builder) {
            return $builder->getWrappedObject();
        });

        $this->beConstructedWith($notificationRepository, $counters);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Email\Batches\Notifications');
    }

    function it_should_run()
    {
        $user = new User();
        $user->guid = '123';
        $user->username = 'testuser';

        $subscription = new EmailSubscription();
        $subscription->setUserGuid('123');

        $this->emailRepository->getList([
            'campaign' => 'when',
            'topic' => 'unread_notifications',
            'value' => true,
            'limit' => 200,
            'offset' => ''
        ])
            ->shouldBeCalled()
            ->willReturn(['data' => [$subscription], 'next' => null]);

        $this->builder->get(['guids' => ['123']])
            ->shouldBeCalled()
            ->willReturn([$user]);

        $this->run();
    }
}
