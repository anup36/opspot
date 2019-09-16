<?php

namespace Spec\Opspot\Core\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Opspot\Core\Data\MongoDB\Client as MongoClient;

class FactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Boost\Factory');
    }

    public function it_should_build_a_handler(MongoClient $db)
    {
        $this::build("Newsfeed", Argument::any(), $db)->shouldHaveType('Opspot\Core\Boost\Newsfeed');
    }

    public function it_should_throw_an_error_if_handler_doesnt_exist()
    {
        $this->shouldThrow("\Exception")->during("build", array("FakeBoost"));
    }
}
