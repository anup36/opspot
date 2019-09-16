<?php

namespace Spec\Opspot\Core\Suggestions;

use Opspot\Entities\User;
use Opspot\Common\Repository\Response;
use Opspot\Core\Suggestions\Manager;
use Opspot\Core\Suggestions\Suggestion;
use Opspot\Core\Suggestions\Repository;
use Opspot\Core\Subscriptions\Manager as SubscriptionsManager;
use Opspot\Core\EntitiesBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;
    private $entitiesBuilder;

    function let(
        Repository $repository,
        EntitiesBuilder $entitiesBuilder,
        SubscriptionsManager $subscriptionsManager
    )
    {
        $this->beConstructedWith($repository, $entitiesBuilder, $subscriptionsManager);
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_a_list_of_suggested_users()
    {
        $response = new Response();
        $response[] = (new Suggestion)
            ->setEntityGuid(456);

        $response[] = (new Suggestion)
            ->setEntityGuid(789);

        $this->repository->getList([
                'limit' => 24,
                'paging-token' => '',
                'user_guid' => 123,
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->setUser((new User)->set('guid', 123));

        $this->entitiesBuilder->single(456)
            ->shouldBeCalled()
            ->willReturn((new User)->set('guid', 456));

        $this->entitiesBuilder->single(789)
            ->shouldBeCalled()
            ->willReturn((new User)->set('guid', 789));

        $newResponse = $this->getList([ 'limit' => 24 ]);

        $newResponse[0]->getEntityGuid()
            ->shouldBe(456);
        $newResponse[0]->getEntity()->getGuid()
            ->shouldBe(456);

        $newResponse[1]->getEntityGuid()
            ->shouldBe(789);
        $newResponse[1]->getEntity()->getGuid()
            ->shouldBe(789);
    }

    

}
