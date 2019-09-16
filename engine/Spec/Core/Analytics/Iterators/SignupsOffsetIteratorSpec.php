<?php

namespace Spec\Opspot\Core\Analytics\Iterators;

use Opspot\Core\Analytics\Iterators\SignupsOffsetIterator;
use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\EntitiesBuilder;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Opspot\Mocks\Cassandra\Rows;

class SignupsOffsetIteratorSpec extends ObjectBehavior
{
    /** @var Client */
    protected $db;
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    function let(Client $db, EntitiesBuilder $entitiesBuilder)
    {
        $this->beConstructedWith($db, $entitiesBuilder);
        $this->db = $db;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SignupsOffsetIterator::class);
    }

    function it_should_get_the_users(User $user1, User $user2)
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT * from entities_by_time where key='user' and column1>?"
                && $built['values'] = [''];
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([
                ['column1' => '1234'],
                ['column1' => '5678']
            ], ''));

        $this->entitiesBuilder->get(['guids' => ['1234', '5678']])
            ->shouldBeCalled()
            ->willReturn([$user1, $user2]);

        $this->setPeriod(20);

        $this->rewind();
        $this->current()->shouldReturn($user1);

        $this->next();
        $this->current()->shouldReturn($user2);
    }
}
