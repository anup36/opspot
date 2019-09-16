<?php

namespace Spec\Opspot\Core\Comments\Legacy;

use Opspot\Core\Comments\Comment;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Comments\Legacy\Entity');
    }

    function it_should_build()
    {
        $this
            ->build([
                'parent_guid' => 0,
                'guid' => 6000,
                'owner_guid' => 1000,
                'container_guid' => 1000,
                'time_created' => 123123123,
                'time_updated' => 123123124,
                'access_id' => 2,
                'description' => 'phpspec',
                'custom_type' => 'test',
                'mature' => true,
                'edited' => true,
                'spam' => true,
                'deleted' => true,
            ])
            ->shouldReturnAnInstanceOf(Comment::class);
    }
}
