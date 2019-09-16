<?php

namespace Spec\Opspot\Core\Security\AbuseGuard;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Data\ElasticSearch\Client;
use Opspot\Entities\User;
use Opspot\Core\Security\AbuseGuard\AccusedEntity;

class RecoverSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Security\AbuseGuard\Recover');
    }

    function it_should_set_accused(AccusedEntity $accused)
    {
        $this->setAccused($accused)->shouldReturn($this);
    }

    function it_should_recover(Client $client, AccusedEntity $accused, User $user)
    {
        $this->beConstructedWith($client);

        $user->get('guid')->willReturn(123);
        $accused->getUser()->willReturn($user);
        $this->setAccused($accused);

        $this->recover()->shouldBe(true);
    }
}
