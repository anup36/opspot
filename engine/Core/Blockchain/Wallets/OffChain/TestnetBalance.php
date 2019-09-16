<?php
namespace Opspot\Core\Blockchain\Wallets\OffChain;

use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;
use Opspot\Entities\User;

class TestnetBalance
{

    /** @var Sums */
    private $sums;

    /** @var User */
    private $user;

    public function __construct($sums = null)
    {
        $this->sums = $sums ?: new TestnetSums;
    }

    /**
     * Sets the user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return the balance
     * @return double
     */
    public function get()
    {
        return $this->sums
            ->setUser($this->user)
            ->getBalance();
    }
}
