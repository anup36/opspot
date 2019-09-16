<?php

namespace Spec\Opspot\Core\Email\Campaigns;

use Opspot\Core\Di\Di;
use Opspot\Core\Email\Mailer;
use Opspot\Core\Email\Template;
use Opspot\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WhenNotificationsSpec extends ObjectBehavior
{
    function let(Template $template, Mailer $mailer)
    {
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Email\Campaigns\WhenNotifications');
    }

    function it_should_send_an_email(Template $template,Mailer $mailer, User $user)
    {
        $this->beConstructedWith($template, $mailer);

        //$user->guid = '123';

        $user->get('guid')->shouldBeCalled()->willReturn('123');
        $user->get('name')->shouldBeCalled()->willReturn('Test User');
        $user->get('username')->shouldBeCalled()->willReturn('testuser');
        //$user->setEmail('testuser@ops.doesntexist.com');
        $user->getEmail()
            ->shouldBeCalled()
            ->wilLReturn('testuser@ops.doesntexist.com');

        $this->setUser($user);

        $amount = 10;
        $this->setAmount($amount);

        $CONFIG = Di::_()->get('Config');

        $CONFIG->opspot_debug = false;

        $template->setTemplate('default.tpl')->shouldBeCalled();
        $template->setBody('./Templates/unread-notifications.tpl')->shouldBeCalled();

        $template->set('user', $user)->shouldBeCalled();
        $template->set('username', $user->username)->shouldBeCalled();
        $template->set('email', 'testuser@ops.doesntexist.com')->shouldBeCalled();

        $template->set('campaign', 'when')->shouldBeCalled();
        $template->set('topic', 'unread_notifications')->shouldBeCalled();

        $template->set('amount', $amount)->shouldBeCalled();

        $mailer->queue(Argument::that(function($message)use($amount) {
            return $message->to[0]['name'] == 'Test User' && $message->to[0]['email'] == 'testuser@ops.doesntexist.com'
                && $message->subject = "You have {$amount} new unread notifications";
        }))->shouldBeCalled();

        $this->send();
    }
}
