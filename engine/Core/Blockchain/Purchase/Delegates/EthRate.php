<?php
/**
 * Eth Rate
 *
 * @author Mark
 */

namespace Opspot\Core\Blockchain\Purchase\Delegates;

use Opspot\Core\Blockchain\Config;
use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\Data\Cassandra\Prepared\Custom;
use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;

class EthRate
{
    /** @var Client */
    protected $cql;

    /**
     * Whitelist constructor.
     * @param null $config
     * @param null $ethereumClient
     */
    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @return int
     */
    public function get()
    {
        $cql = "SELECT * from config WHERE key=?";
        $values = ['token_rate'];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $rows = $this->cql->request($query);
        } catch (\Exception $e) {
            return null;
        }

        if (!$rows || !$rows[0]) {
            return null;
        }

        return (int) $rows[0]['value'];
    }

    /**
     * @param int $value
     * @return void
     */
    public function set($value)
    {
        $cql = "INSERT INTO config (key, value) VALUES (?,?)";
        $values = ['token_rate', (string) $value];

        $query = new Custom();
        $query->query($cql, $values);

        try {
            $rows = $this->cql->request($query);
        } catch (\Exception $e) {
            return null;
        }  
    }
}
