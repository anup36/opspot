<?php

namespace Spec\Opspot\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Di\Di;
use Opspot\Core\Monetization\Manager;

class AdminSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Monetization\Admin');
    }

    function it_should_get_admin_queue(Manager $manager)
    {
        Di::_()->bind('Monetization\Manager', function($di) use ($manager) {
            return $manager->getWrappedObject();
        });

        $manager->get(Argument::type('array'))
            ->willReturn([
                [ 'guid' => '1', 'user_guid' => null, 'amount' => 150 ],
                [ 'guid' => '2', 'user_guid' => null, 'amount' => 250 ],
            ])
            ->shouldBeCalled();

        $this->getQueue()->shouldReturn([
            [ 'guid' => '1', 'user_guid' => null, 'amount' => 1.5 ],
            [ 'guid' => '2', 'user_guid' => null, 'amount' => 2.5 ],
        ]);
    }
}
