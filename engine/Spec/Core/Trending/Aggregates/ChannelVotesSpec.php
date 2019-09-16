<?php

namespace Spec\Opspot\Core\Trending\Aggregates;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Data\ElasticSearch\Client;

class ChannelVotesSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Trending\Aggregates\ChannelVotes');
    }

    function it_should_return_comments_with_new_score(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::type('Opspot\\Core\\Data\\ElasticSearch\\Prepared\\Search'))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'entities' => [
                        'buckets' => [
                            [ 'key' => 123, 'doc_count' => 50, 'uniques' => [ 'value' => 50 ] ],
                            [ 'key' => 456, 'doc_count' => 25, 'uniques' => [ 'value' => 25 ] ]
                        ]
                    ]
                ]
            ]);

        $this->get()->shouldReturn([
            123 => 100,
            456 => 50
        ]);
    }
}
