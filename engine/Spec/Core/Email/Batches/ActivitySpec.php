<?php

namespace Spec\Opspot\Core\Email\Batches;

use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\EntitiesBuilder;
use Opspot\Core\Trending\Repository;
use PhpSpec\ObjectBehavior;

class ActivitySpec extends ObjectBehavior
{
    protected $client;
    protected $repository;
    protected $entities;

    function let(Client $client, Repository $repository, EntitiesBuilder $entities)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->entities = $entities;
        $this->beConstructedWith($client, $repository, $entities);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Email\Batches\Activity');
    }

    function it_should_set_the_period()
    {
        $this->setPeriod('daily')->shouldReturnAnInstanceOf('Opspot\Core\Email\Batches\Activity');
    }

    function it_should_set_the_offset()
    {
        $this->setOffset('123')->shouldReturnAnInstanceOf('Opspot\Core\Email\Batches\Activity');
    }

    function it_should_run()
    {
        $this->repository->getList(['type' => 'newsfeed', 'limit' => 12])
            ->shouldBeCalled()
            ->willReturn([
                'guids' => [
                    '1000',
                    '1001',
                    '1002'
                ],
                'token' => ''
            ]);

        $this->entities->get(['type' => 'activity', 'guids' => ['1000', '1001', '1002']])
            ->shouldBeCalled()
            ->willReturn([]);

        $this->setPeriod('daily');
        $this->setOffset('');

        $this->run();
    }
}
