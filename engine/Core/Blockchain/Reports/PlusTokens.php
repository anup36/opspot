<?php
/**
 * Plus Token Report
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

class PlusTokens extends BoostTokens
{
    /**
     * Contructor
     * @param Core\Config\Config $config
     */
    public function __construct($config = null) {
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->token_wallet = $blockchainConfig['contracts']['bonus']['wallet_address'];
    }
}
