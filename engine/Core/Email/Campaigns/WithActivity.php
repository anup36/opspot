<?php


namespace Opspot\Core\Email\Campaigns;


use Opspot\Core\Config;
use Opspot\Core\Email\Mailer;
use Opspot\Core\Email\Message;
use Opspot\Core\Email\Template;
use Opspot\Entities\Activity;
use Opspot\Entities\User;

class WithActivity extends EmailCampaign
{
    protected $template;
    protected $mailer;

    protected $posts;

    public function __construct(Template $template = null, Mailer $mailer = null)
    {
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
        $this->campaign = 'with';
        $this->topic = 'top_posts';
    }

    /**
     * @param Activity[] $posts
     * @return $this
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');

        $this->template->setBody("./Templates/top-posts.tpl");

        $validatorHash = sha1($this->campaign . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('user', $this->user);
        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());

        $this->template->set('posts', $this->posts);

        $this->template->set('campaign', $this->campaign);
        $this->template->set('topic', $this->topic);

        $this->template->set('validator', $validatorHash);

        $subject = 'Trending posts';

        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-',
                [$this->user->guid, sha1($this->user->getEmail()), sha1($this->campaign . $this->topic . time())]))
            ->setSubject($subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->queue($message);
    }

}
