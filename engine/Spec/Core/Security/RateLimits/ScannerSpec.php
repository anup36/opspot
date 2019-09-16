<?php

namespace Spec\Opspot\Core\Security\RateLimits;

use Opspot\Core\Security\RateLimits\Manager;
use Opspot\Core\Security\RateLimits\Scanner;
use Opspot\Core\Security\RateLimits\Maps;
use Opspot\Core\Trending\Aggregates\Subscribe;
use Opspot\Entities\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ScannerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Scanner::class);
    }

    function it_should_run_over_maps(
        Manager $manager,
        Subscribe $subscribeAgg
    )
    {
        $maps = [
            'interaction:subscribe' => [
                'interaction' => 'subscribe',
                'period' => 300, //5 minutes
                'threshold' => 50, //50 per 5 minutes, 10 per minute
                'aggregates' => [
                    $subscribeAgg,
                ],
            ],
        ];

        $this->beConstructedWith($manager, $maps);

        $subscribeAgg->setFrom((time() - 300) * 1000)
            ->shouldBeCalled()
            ->willReturn($subscribeAgg);

        $subscribeAgg->setTo(time() * 1000)
            ->shouldBeCalled()
            ->willReturn($subscribeAgg);

        $subscribeAgg->get()
            ->shouldBeCalled()
            ->willReturn([
                10001 => 50,
                10002 => 1,
                10003 => 100
            ]);

        $manager->setInteraction('subscribe')
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setLimitLength(300)
            ->shouldBeCalled();

        $user = new User;
        $manager->setUser($user)
            ->shouldBeCalledTimes(2)
            ->willReturn($manager);

        $manager->isLimited()
            ->shouldBeCalledTimes(2)
            ->willReturn(false);

        $manager->impose()
            ->shouldBeCalledTimes(2);

        $this->run();
    }

    function it_should_run_over_maps_but_skip_if_already_limited(
        Manager $manager,
        Subscribe $subscribeAgg
    )
    {
        $maps = [
            'interaction:subscribe' => [
                'interaction' => 'subscribe',
                'period' => 300, //5 minutes
                'threshold' => 50, //50 per 5 minutes, 10 per minute
                'aggregates' => [
                    $subscribeAgg,
                ],
            ],
        ];

        $this->beConstructedWith($manager, $maps);

        $subscribeAgg->setFrom((time() - 300) * 1000)
            ->shouldBeCalled()
            ->willReturn($subscribeAgg);

        $subscribeAgg->setTo(time() * 1000)
            ->shouldBeCalled()
            ->willReturn($subscribeAgg);

        $subscribeAgg->get()
            ->shouldBeCalled()
            ->willReturn([
                10001 => 50,
                10002 => 1,
                10003 => 100
            ]);

        $manager->setInteraction('subscribe')
            ->shouldBeCalled()
            ->willReturn($manager);

        $manager->setLimitLength(300)
            ->shouldBeCalled();

        $user = new User;
        $manager->setUser($user)
            ->shouldBeCalledTimes(2)
            ->willReturn($manager);

        $manager->isLimited()
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $manager->impose()
            ->shouldNotBeCalled();

        $this->run();
    }

}
