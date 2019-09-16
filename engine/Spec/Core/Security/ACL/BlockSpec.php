<?php

namespace Spec\Opspot\Core\Security\ACL;

use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\Data\Cassandra\Thrift\Indexes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Opspot\Core\Data\Call as Call;
use Opspot\Core\Data\Call as DataPool;
use Opspot\Entities\Entity;

class BlockSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Security\ACL\Block');
    }

    //function it_should_listen_to_interact_acl_event(Entity $entity){
      //$this->
    //}

    public function it_should_return_if_blocked(Call $db, Indexes $indexes, Client $cql)
    {
        //$db->beConstructedWith($indexes, $db);
        $db->getRow("acl:blocked:foo", Argument::any())->willReturn(array(
        "bar" => time()
      ));
        $cql->request(Argument::any())->willReturn([['column1' => 'bar'], ['column1' => 'foo']]);

        $this->beConstructedWith($indexes, $cql);
        $this->isBlocked("bar", "foo")->shouldReturn(true);
        $this->isBlocked("boo", "foo")->shouldReturn(false);
    }

    public function it_should_add_a_user_to_the_list(Indexes $indexes, Client $cql)
    {
        //$indexes->beConstructedWith(['entities_by_time']);
        $indexes->insert("acl:blocked:foo", Argument::type('array'))->willReturn("bar");

        $this->beConstructedWith($indexes, $cql);
        $this->block("bar", "foo")->shouldReturn("bar");
    }

    public function it_should_remove_a_user_from_the_list(Indexes $indexes, Client $cql)
    {
        $indexes->remove("acl:blocked:foo", array("bar"))->willReturn(true);


        $this->beConstructedWith($indexes, $cql);
        $this->unBlock("bar", "foo")->shouldReturn(true);
    }
}
