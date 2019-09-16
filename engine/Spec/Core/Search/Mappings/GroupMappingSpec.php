<?php

namespace Spec\Opspot\Core\Search\Mappings;

use Opspot\Entities\Group;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GroupMappingSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Search\Mappings\GroupMapping');
    }

    //function it_should_map_a_group(
    //    Group $group
    //)
    //{
        // TODO: Find the way to mock __call('getOwnerObj')
    //}
}
