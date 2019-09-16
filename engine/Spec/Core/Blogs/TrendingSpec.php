<?php

namespace Spec\Opspot\Core\Blogs;

use Opspot\Common\Repository\Response;
use Opspot\Core\Comments\Repository;
use Opspot\Core\Trending\Repository as TrendingRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TrendingSpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var TrendingRepository */
    protected $trendingRepository;

    function let(
        Repository $repository,
        TrendingRepository $trendingRepository
    ) {
        $this->beConstructedWith($repository, $trendingRepository);

        $this->repository = $repository;
        $this->trendingRepository = $trendingRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Blogs\Trending');
    }

    function it_should_get_list(Response $response)
    {
        $this->trendingRepository->getList([
            'phpspec' => true,
            'type' => 'blogs',
        ])
            ->shouldBeCalled()
            ->willReturn([
                'guids' => [ 5000, 5001 ],
                'token' => 'oggabooga',
            ]);

        $this->repository->getList([
            'guids' => [ 5000, 5001 ]
        ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this
            ->getList([ 'phpspec' => true ])
            ->shouldReturn($response);
    }
}
