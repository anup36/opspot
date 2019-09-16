<?php

/**
 * Opspot Withholding Sums
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain\Wallets\OffChain\Withholding;

use Cassandra;
use Opspot\Core\Config;
use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\Data\Cassandra\Prepared\Custom;
use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;
use Opspot\Entities\User;

class Sums
{
    /** @var Client */
    protected $db;

    /** @var Config */
    protected $config;

    /** @var int */
    protected $user_guid;

    public function __construct($db = null, $config = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param int|User $user_guid
     * @return Sums
     */
    public function setUserGuid($user_guid)
    {
        if (is_object($user_guid)) {
            $user_guid = $user_guid->guid;
        }

        $this->user_guid = $user_guid;
        return $this;
    }

    /**
     * Gets the total sum of withholding funds for a user
     * @return string
     */
    public function get()
    {
        if ($this->config->get('blockchain')['disable_creditcards']) {
            return '0';
        }

        $cql = "SELECT sum(amount) as total FROM withholdings WHERE user_guid = ?";
        $values = [
            new Cassandra\Varint($this->user_guid)
        ];

        $query = new Custom();
        $query->query($cql, $values);

        $rows = $this->db->request($query);
        return (string) BigNumber::_($rows[0]['total']);
    }
}
