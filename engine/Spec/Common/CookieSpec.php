<?php

namespace Spec\Opspot\Common;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Opspot\Core\Config;

class CookieSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Common\Cookie');
    }

    function it_should_not_create_a_secure_cookie_if_disabled(Config $config)
    {
        $this->beConstructedWith($config);

        $this->isSecure()->shouldBe(true);

        $config->get('disable_secure_cookies')->willReturn(true);

        $this->setName('spec')
            ->setValue('test')
            ->create();

        $this->isSecure()->shouldBe(false);
    }

}