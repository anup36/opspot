<?php

namespace Opspot\Core\Email\Campaigns\UserRetention;

use Opspot\Core\Email\Campaigns\EmailCampaign;
use Opspot\Core\Email\Template;
use Opspot\Core\Email\Mailer;
use Opspot\Core\Email\Message;
use Opspot\Core\Email\Manager;
use Opspot\Traits\MagicAttributes;
use Opspot\Core\Di\Di;

class GoneCold extends EmailCampaign
{
    use MagicAttributes;
    protected $db;
    protected $template;
    protected $mailer;
    protected $amount;
    protected $campaign;

    public function __construct(Template $template = null, Mailer $mailer = null, Manager $manager = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->manager = $manager ?: Di::_()->get('Email\Manager');
        $this->campaign = 'global';
        $this->topic = 'opspot_tips';
        $this->state = 'cold';
    }

    public function build()
    {
        $this->template->setTemplate('default.tpl');
        $this->template->setBody('./Templates/gone_cold.tpl');
        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->getGUID());
        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('state', $this->state);

        $subject = 'What fascinates you?';

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign.$this->topic.time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        return $message;
    }

    public function send()
    {
        //send email
        if ($this->canSend()) {
            $this->mailer->queue($this->build());
        }
    }
}
