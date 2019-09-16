<?php

namespace Spec\Opspot\Core\Channels;

use Opspot\Core\Channels\Manager;
use Opspot\Core\Channels\Delegates\DeleteUser;
use Opspot\Core\Channels\Delegates\DeleteArtifacts;
use Opspot\Core\Channels\Delegates\Logout;
use Opspot\Entities\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    /** @var Delegates\DeleteUser */
    private $deleteUserDelegate;

    /** @var Delegates\DeleteArtifacts */
    private $deleteArtifactsDelegate;

    /** @var Delegates\Logout */
    private $logoutDelegate;

    function let(
        DeleteUser $deleteUserDelegate,
        DeleteArtifacts $deleteArtifactsDelegate,
        Logout $logoutDelegate
    )
    {
        $this->beConstructedWith($deleteUserDelegate, $deleteArtifactsDelegate, $logoutDelegate);
        $this->deleteUserDelegate = $deleteUserDelegate;
        $this->deleteArtifactsDelegate = $deleteArtifactsDelegate;
        $this->logoutDelegate = $logoutDelegate;

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_delete_a_channel(
        DeleteUser $deleteUserDelegate,
        DeleteArtifacts $deleteArtifactsDelegate,
        Logout $logoutDelegate
    )
    {
        $user = new User();
        $user->guid = 123;

        $this->setUser($user)
            ->shouldReturn($this);

        $this->deleteUserDelegate->delete($user)
            ->shouldBeCalled();

        $this->deleteArtifactsDelegate->queue($user)
            ->shouldBeCalled();

        $this->logoutDelegate->logout($user)
            ->shouldBeCalled();

        $this->delete();
    }
}
