<?php

namespace Spec\Opspot\Core\Blockchain\Purchase\Delegates;

use Opspot\Core\Blockchain\Purchase\Delegates\IssuedTokenEmail;
use Opspot\Core\Blockchain\Purchase\Purchase;
use Opspot\Core\Config;
use Opspot\Core\Data\lookup;
use Opspot\Core\Di\Di;
use Opspot\Core\Email\Campaigns\Custom;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IssuedTokenEmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IssuedTokenEmail::class);
    }

    function it_should_send(Config $config, Custom $campaign, lookup $lookup, Purchase $purchase)
    {
        $this->beConstructedWith($config, $campaign);

        Di::_()->bind('Database\Cassandra\Data\Lookup', function ($di) use ($lookup) {
            return $lookup->getWrappedObject();
        });

        $purchase->getRequestedAmount()
            ->shouldBeCalled()
            ->willReturn(10000000000000000000);

        $purchase->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('123');

        $campaign->setUser(Argument::type('Opspot\Entities\User'))
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->setSubject('Your purchase of 10 Tokens has now been issued.')
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->setTemplate('issued-token-purchase.md')
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->setTopic('billing')
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->setCampaign('tokens')
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->setVars([
            'date' => date('d-M-Y', time()),
            'amount' => 10
        ])
            ->shouldBeCalled()
            ->willReturn($campaign);
        $campaign->send()
            ->shouldBeCalled();


        $this->send($purchase);
    }
}
