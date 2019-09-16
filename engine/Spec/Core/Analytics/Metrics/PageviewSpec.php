<?php

namespace Spec\Opspot\Core\Analytics\Metrics;

use Opspot\Core\Analytics\Metrics\Pageview;
use Opspot\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageviewSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    function let(Client $client)
    {
        $this->beConstructedWith($client);

        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Pageview::class);
    }

    function it_should_get_analytics()
    {
        $this->client->request(Argument::type('Opspot\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'pageviews' => [
                        'buckets' => [
                            [
                                'key' => 1529581013443,
                                'doc_count' => 50,
                                'uniques' => [
                                    'value' => 50
                                ]
                            ],
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldHaveCount(1);
        $this->get()->shouldReturn([
            [
                'timestamp' => 1529581013443 / 1000,
                'date' => date('d-m-Y', 1529581013443 / 1000),
                'unique' => 50,
                'total' => 50
            ]
        ]);
    }
}
