<?php

namespace Spec\Opspot\Core\Boost\Network;

use Opspot\Core\Boost\Repository;
use Opspot\Core\Data\MongoDB;
use Opspot\Core\Di\Di;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MetricsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Boost\Network\Metrics');
    }

    function it_should_get_backlog_count(MongoDB\Client $mongo)
    {
        $mongo->count(Argument::containingString('boost'), Argument::any())
            ->shouldBeCalled()
            ->willReturn(3);


        $this->beConstructedWith($mongo);

        $this->getBacklogCount('newsfeed', '123')->shouldReturn(3);
    }

    function it_should_get_priority_backlog_count(MongoDB\Client $mongo)
    {
        $mongo->count(Argument::containingString('boost'), Argument::any())
            ->shouldBeCalled()
            ->willReturn(3);


        $this->beConstructedWith($mongo);

        $this->getPriorityBacklogCount('newsfeed', '123')->shouldReturn(3);
    }

    function it_should_get_backlog_impressions_sum(MongoDB\Client $mongo)
    {
        $total = new \stdClass();
        $total->total = 10;
        $result = ['total' => $total];

        $mongo->aggregate(Argument::containingString('boost'), Argument::any())
            ->shouldBeCalled()
            ->willReturn($result);


        $this->beConstructedWith($mongo);

        $this->getBacklogImpressionsSum('newsfeed')->shouldReturn(10);
    }

}
