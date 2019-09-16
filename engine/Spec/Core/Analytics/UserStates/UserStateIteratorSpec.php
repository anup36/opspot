<?php

namespace Spec\Opspot\Core\Analytics\UserStates;

use Opspot\Core\Analytics\UserStates\UserStateIterator;
use PhpSpec\ObjectBehavior;
use Opspot\Core\Data\ElasticSearch\Client;
use Opspot\Core\Analytics\UserStates\UserState;
use Opspot\Core\Data\ElasticSearch\Prepared\Search;

class UserStateIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $client;

    public function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserStateIterator::class);
    }

    public function it_should_get_the_list()
    {
        $prepared = new Search();
        $prepared->query($this->getMockData('user_state_changes_query.json'));
        $this->setReferenceDate(1549497600);
        $this->client->request($prepared)
        ->shouldBeCalled()
        ->willReturn($this->getMockData('user_state_changes_results.json'));
        $this->next();
        $this->valid()->shouldBe(true);
        $this->current()->shouldHaveType(UserState::class);
        $this->current()->getUserGuid()->shouldEqual('933120961241157645');
        $this->current()->getReferenceDateMs()->shouldEqual(1549238400000);
        $this->current()->getState()->shouldEqual('curious');
        $this->current()->getPreviousState()->shouldEqual('resurrected');
        $this->current()->getActivityPercentage()->shouldEqual('0.14');
    }

    private function getMockData($filename)
    {
        return json_decode(file_get_contents(__DIR__."/MockData/${filename}"), true);
    }
}
