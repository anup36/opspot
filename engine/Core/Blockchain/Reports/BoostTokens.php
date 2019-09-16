<?php
/**
 * Boost Token Report
 *
 * @author Martin Alejandro Santangelo
 */

namespace Opspot\Core\Blockchain\Reports;

use Opspot\Core\Di\Di;
use Opspot\Core\Util\BigNumber;
use Opspot\Core\Blockchain\EthPrice;
use Opspot\Core\Blockchain\Services\Poloniex;
use Opspot\Core\Blockchain\Services\Etherscan;
use Opspot\Core\Blockchain\Services\EtherscanTransactionsByDate;

class BoostTokens extends AbstractReport
{
    /** @var string */
    protected $token_wallet;

    /** @var array columns names */
    protected $columns = [
        'Date & Time',
        'Timestamp',
        'Block Number',
        'Tx Hash',
        'From',
        'Opspot',
    ];

    /** @var array allower parameters for the report */
    protected $params = ['from', 'to'];

    /** @var array required parameters for the report */
    protected $required = ['from', 'to'];

    /**
     * Contructor
     * @param Core\Config\Config $config
     */
    public function __construct($config = null) {
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->token_wallet = $blockchainConfig['contracts']['boost']['contract_address'];
    }

    /**
     * Get report result
     *
     * @return array
     */
    public function get()
    {
        if ($this->from > $this->to) {
            throw new \Exception('Wrong interval');
        }

        $service = new EtherscanTransactionsByDate(new Etherscan);

        // fetch data from etherscan service
        $data = $service->setAddress($this->token_wallet)
            ->setType('token')
            ->getRange($this->from, $this->to);

        // format output
        return array_map(function($row) use($ethPrice) {
            $opspot = BigNumber::fromPlain($row['value'], 18);

            return [
                date(DATE_ISO8601, $row['timeStamp']), // friendly time
                $row['timeStamp'],   // timestamp
                $row['blockNumber'], // block number
                $row['hash'],        // tx hash
                $row['from'],        // origin address
                $opspot->toString(),  // Opspot amount
            ];
        }, $data);
    }
}
