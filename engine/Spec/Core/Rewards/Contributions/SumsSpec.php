<?php

namespace Spec\Opspot\Core\Rewards\Contributions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Opspot\Core\Data\Cassandra\Client;

class SumsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Rewards\Contributions\Sums');
    }

    function it_sould_get_a_balance(Client $db)
    {
        $this->beConstructedWith($db);

        $db->request(Argument::any())
            ->willReturn([
                [ 'amount' => 12 ]
            ]);

        $this->getAmount()
            ->shouldReturn('12');
    }

}
