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
use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;
use Opspot\Core\Analytics\Iterators;

class TestnetRewards 
{
    protected $db;
    protected $template;
    protected $mailer;

    protected $user;
    protected $subject = "";
    protected $templateKey = "";
    protected $topic = "opspot_news";
    protected $campaign = "global";

    public function __construct(Call $db = null, Template $template = null, Mailer $mailer = null)
    {
        $this->db = $db ?: new Call('entities_by_time');
        $this->template = $template ?: new Template();
        $this->mailer = $mailer ?: new Mailer();
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setTemplate($template)
    {
        $this->templateKey = $template;
        return $this;
    }

    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function send()
    {
        $this->template->setTemplate('default.tpl');
        $this->template->setBody("./Templates/testnet-rewards.md.tpl");
        $this->template->toggleMarkdown(true);

        if ($this->user->getPhoneNumberHash()) {
        //    return;
        }

        $balance = Di::_()->get('Blockchain\Wallets\OffChain\TestnetBalance');
        $balance->setUser($this->user);
        $balanceVal = BigNumber::fromPlain($balance->get(), 18);
        $balanceVal = round($balanceVal->toString(), 2);

        if ($balanceVal < 0) {
            return;
        }

        $this->subject = "Action needed to keep your $balanceVal tokens";

        $validatorHash = sha1($this->campaign . $this->user->guid . Config::_()->get('emails_secret'));

        $this->template->set('username', $this->user->username);
        $this->template->set('email', $this->user->getEmail());
        $this->template->set('guid', $this->user->guid);
        $this->template->set('user', $this->user);
        $this->template->set('balance', (string) $balanceVal);
        $this->template->set('topic', $this->topic);
        $this->template->set('campaign', $this->campaign);
        $this->template->set('validator', $validatorHash);


        $message = new Message();
        $message->setTo($this->user)
            ->setMessageId(implode('-', [ $this->user->guid, sha1($this->user->getEmail()), $validatorHash ]))
            ->setSubject($this->subject)
            ->setHtml($this->template);

        //send email
        $this->mailer->send($message);
    }

}
