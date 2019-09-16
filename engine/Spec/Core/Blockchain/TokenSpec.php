<?php

namespace Spec\Opspot\Core\Blockchain;

use Opspot\Core\Blockchain\Contracts\OpspotToken;
use Opspot\Core\Blockchain\Manager;
use Opspot\Core\Blockchain\Services\Ethereum;
use PhpSpec\ObjectBehavior;

class TokenSpec extends ObjectBehavior
{
    /** @var Manager */
    private $manager;
    /** @var Ethereum */
    private $client;

    function let(Manager $manager, Ethereum $client, OpspotToken $contract)
    {
        $this->manager = $manager;
        $this->client = $client;

        $contract->getAddress()->willReturn('opspot_token_addr');
        $contract->getExtra()->willReturn(['decimals' => 18]);

        $this->manager->getContract('token')->willReturn($contract);

        $this->beConstructedWith($manager, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Blockchain\Token');
    }

    function it_should_return_the_balance()
    {
        $this->client->call('opspot_token_addr', 'balanceOf(address)', ['foo'])
            ->shouldBeCalled()
            ->willReturn('0x2B5E3AF16B1880000');

        $this->balanceOf('foo')->shouldBe('50000000000000000000');
    }

    function it_should_return_the_total_supply()
    {
        $this->client->call('opspot_token_addr', 'totalSupply()', [])
            ->shouldBeCalled()
            ->willReturn('0xDE0B6B3A7640000');

        $this->totalSupply()->shouldReturn('1.000000000000000000');
    }

    function it_should_transform_an_amount_to_token_unit()
    {
        $this->toTokenUnit(10)->shouldReturn('10000000000000000000');
    }

    function it_should_transform_an_amount_from_token_unit()
    {
        $this->fromTokenUnit(10000000000000000000)->shouldReturn('10.000000000000000000');
    }

}
