<?php

namespace Spec\Opspot\Core\Email;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PHPMailer;

use Opspot\Core\Queue\Client as Queue;
use Opspot\Core\Email\SpamFilter;
use Opspot\Core\Email\Message;

class MailerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Email\Mailer');
    }

    function it_should_not_send_a_blacklist_domain(PHPMailer $mailer, Queue $queue, SpamFilter $filter, Message $message)
    {
        $this->beConstructedWith($mailer, $queue, $filter);

        $message->to = [[ 'email' => 'you@yomail.com', 'name' => 'Spam' ]];
        $message->from = [ 'email' => 'me@ops.doesntexist.com', 'name' => 'Sender' ];
        $this->send($message);

        $this->getStats()->shouldHaveKeyWithValue('failed', 1);
    }
}
