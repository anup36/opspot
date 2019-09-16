<?php

namespace Spec\Opspot\Core\Analytics\Iterators;

use Opspot\Core\Analytics\Iterators\EventsIterator;
use Opspot\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventsIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    function let(Client $client)
    {
        $this->beConstructedWith($client, 'opspot-metrics-*');
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventsIterator::class);
    }

    function it_should_get_the_list()
    {
        $this->client->request(Argument::type('Opspot\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entity_guid.keyword' => [
                        'buckets' => [
                            [
                                'key' => 1529581013443,
                            ],
                            [
                                'key' => 1529581013689,
                            ],
                        ]
                    ]
                ]
            ]);

        $this->setPeriod(strtotime('-1 day'));
        $this->setTerms(['entity_guid.keyword']);

        $this->next();
        $this->current()->shouldReturn(1529581013443);
        $this->next();
        $this->current()->shouldReturn(1529581013689);

    }
}
