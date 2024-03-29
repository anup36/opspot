<?php
namespace Opspot\Core\Email\Campaigns;

use Opspot\Core\Config;
use Opspot\Core\Email\Mailer;
use Opspot\Core\Email\Message;
use Opspot\Core\Email\Template;

class InactiveUsers extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $templateKey;
    protected $subject;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'global';
        $this->topic = 'inactive';
    }

    /**
     * @param string $templateKey
     * @return Promotion
     */
    public function setTemplateKey($templateKey)
    {
        $this->templateKey = $templateKey;
        return $this;
    }

    /**
     * @param string $subject
     * @return Promotion
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;

    }

    /**
     * @return void
     * @throws \Exception
     */
    public function send()
    {

        if (!method_exists($this->user, 'getEmail')) {
            return;
        }

        $this->template->setTemplate('default.tpl');
        $this->template->toggleMarkdown(true);
        $this->template->setBody("./Templates/inactive-users.tpl");

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        //do not reward twice
        $validatorHash = sha1($this->campaign . $this->topic . $this->user->guid . Config::_()->get('emails_secret'));
        $this->template->set('validator', $validatorHash);

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->send($message);
    }

}
