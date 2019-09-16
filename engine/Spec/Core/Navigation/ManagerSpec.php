<?php

namespace Spec\Opspot\Core\Navigation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Opspot\Core\Navigation\Item;
use Opspot\Core\Di\Di;
use Opspot\Core\Data\Cassandra\Client as CassandraClient;

class ManagerSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Navigation\Manager');
    }

    public function it_should_add_an_item_to_a_container(Item $item)
    {
        $this::add($item, "phpspec");
    }

    public function it_should_export_items_for_a_container(Item $item)
    {
        $this::add($item, "phpspec");
        $this::export("phpspec")->shouldHaveKey("phpspec");
    }
}
