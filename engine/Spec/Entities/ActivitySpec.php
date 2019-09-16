<?php

namespace Spec\Opspot\Entities;

use Opspot\Core\Di\Di;
use Opspot\Core\Wire\Sums;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivitySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Entities\Activity');
    }

    public function it_has_wire_totals(Sums $sums)
    {
        $sums->setEntity(Argument::any())
            ->willReturn($sums);
        $sums->getEntity()
            ->willReturn(10);

        Di::_()->bind('Wire\Sums', function ($di) use ($sums) {
            return $sums->getWrappedObject();
        });

        $this->beConstructedWith(null);
        $this->guid = '123';
        $this->getWireTotals()->shouldBeLike([
            'tokens' => 10
        ]);
    }
}
