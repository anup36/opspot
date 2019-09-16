<?php

/**
 * Opspot Withholding Repository
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain\Wallets\OffChain\Withholding;

use Cassandra;
use Opspot\Core\Data\Cassandra\Client;
use Opspot\Core\Di\Di;

class Repository
{
    /** @var Client */
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param Withholding|Withholding[] $withholdings
     * @return bool
     */
    public function add($withholdings) {
        if (!is_array($withholdings)) {
            $withholdings = [ $withholdings ];
        }

        $requests = [];
        $cql = "INSERT INTO withholdings (user_guid, timestamp, tx, type, wallet_address, amount) VALUES (?, ?, ?, ?, ?, ?) USING TTL ?";
        foreach ($withholdings as $withholding) {
            $requests[] = [
                'string' => $cql,
                'values' => [
                    new Cassandra\Varint($withholding->getUserGuid()),
                    new Cassandra\Timestamp($withholding->getTimestamp()),
                    $withholding->getTx(),
                    $withholding->getType(),
                    $withholding->getWalletAddress(),
                    new Cassandra\Varint((string) $withholding->getAmount()),
                    $withholding->getTtl(),
                ]
            ];
        }

        $this->db->batchRequest($requests, Cassandra::BATCH_UNLOGGED);

        return true;
    }
}
