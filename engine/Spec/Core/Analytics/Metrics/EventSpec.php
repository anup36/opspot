<?php

namespace Spec\Opspot\Core\Analytics\Metrics;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Data\ElasticSearch\Client;
use Opspot\Core\Data\ElasticSearch\Prepared\Index;

class EventSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Analytics\Metrics\Event');
    }

    function it_should_set_a_variable()
    {
        $this->setType('hello')->shouldReturn($this);
        $this->getData()->shouldReturn([
            'type' => 'hello'
        ]);
    }

    function it_should_set_camel_case()
    {   
        $this->setOwnerGuid('hello')->shouldReturn($this);
        $this->setNotcamelcase('boo')->shouldReturn($this);
        $this->setSnake_Case('woo')->shouldReturn($this);
        $this->getData()->shouldReturn([
            'owner_guid' => 'hello',
            'notcamelcase' => 'boo',
            'snake__case' => 'woo'
        ]);
    }

    function it_should_push(Client $es, Index $prepared)
    {
        $this->beConstructedWith($es);

        /*$prepared->query([
            'body' => $this->getData(),
            'index' => "opspot-metrics-" . date('m-Y', time()),
            'type' => 'action',
            'client' => [
                'timeout' => 2,
                'connect_timeout' => 1
            ] 
        ])->shouldBeCalled();
        $prepared->build()->shouldBeCalled();*/

        $es->request(Argument::type('Opspot\Core\Data\ElasticSearch\Prepared\Index'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->setType('action');
        $this->push()->shouldBe(true);
        $this->getData()->shouldHaveKey('@timestamp');
    }

}
