<?php
/**
 * Custom Campaign Emails
 */

namespace Opspot\Core\Email\Campaigns;

use Opspot\Core\Config;
use Opspot\Core\Entities;
use Opspot\Core\Data\Call;
use Opspot\Core\Analytics\Timestamps;
use Opspot\Core\Email\Mailer;
use Opspot\Core\Email\Message;
use Opspot\Core\Email\Template;
use Opspot\Helpers;
use Opspot\Entities\User;
use Opspot\Core\Analytics\Iterators;

class Announcement extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $subject = "";
    protected $templateKey = "";
    protected $campaign;
    protected $topic;

    protected $period = 10;
    protected $offset = "";
    protected $dryRun = false;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'global';
        $this->topic = 'exclusive_promotions';
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTemplateKey($key)
    {
        $this->templateKey = $key;
        return $this;
    }

    public function send()
    {
        $this->template->set('points', 0);

        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/$this->templateKey.tpl");

        $validatorHash = sha1($this->campaign . $this->topic . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);
        $this->template->set('validator', $validatorHash);

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-', [$this->user->guid, sha1($this->user->getEmail()), $validatorHash]))
            ->setSubject($this->subject)
            ->setHtml($this->template);
        $this->mailer->queue($message);
    }
}
