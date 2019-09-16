<?php

namespace Spec\Opspot\Core\Notification\Batches;

use Opspot\Core\Notification\Batches\Manager;
use Opspot\Core\Notification\Batches\Repository;
use Opspot\Core\Notification\Batches\BatchSubscription;
use Opspot\Core\Data\Cassandra\Client;
use Spec\Opspot\Mocks\Cassandra\Rows;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    protected $db;

    function let(Client $db)
    {
        $this->db = $db;
        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_single_subscription(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(123)
            ->setBatchId('phpspec');

        $this->db->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Rows([
                [
                    'batch_id' => 'phpspec',
                    'user_guid' => 123,
                ]
            ], ''));

        $returned = $this->get($subscription);
        $returned->getUserGuid()
            ->shouldBe($subscription->getUserGuid());
        $returned->getBatchId()
            ->shouldBe($subscription->getBatchId());
    }

    function it_should_add_batch_to_db(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(456)
            ->setBatchId('phpspec');

        $this->db->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($subscription)
            ->shouldBe(true);
    }

    function it_should_remove_from_db(\PDOStatement $statement)
    {
        $subscription = new BatchSubscription();
        $subscription
            ->setUserGuid(789)
            ->setBatchId('phpspec');

        $this->db->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete($subscription)
            ->shouldBe(true);
    }

}
