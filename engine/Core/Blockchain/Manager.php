<?php

/**
 * Blockchain Manager
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain;

use Opspot\Core\Di\Di;

class Manager
{
    protected $config;
    protected $contracts = [];
    protected static $infuraProxyEndpoint = 'api/v2/blockchain/proxy/';

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');

        $this->initContracts();
    }

    protected function initContracts()
    {
        $blockchainConfig = $this->config->get('blockchain');

        if ($blockchainConfig['token_address']) {
            $this->contracts['token'] = Contracts\OpspotToken::at($blockchainConfig['token_address']);
        }

        if ($blockchainConfig['contracts']['wire']['contract_address']) {
            $this->contracts['wire'] = Contracts\OpspotWire::at($blockchainConfig['contracts']['wire']['contract_address']);
        }

        if ($blockchainConfig['contracts']['boost']['contract_address']) {
            $this->contracts['boost'] = Contracts\OpspotBoost::at($blockchainConfig['contracts']['boost']['contract_address']);
        }

        if ($blockchainConfig['contracts']['withdraw']['contract_address']) {
            $this->contracts['withdraw'] = Contracts\OpspotWithdraw::at($blockchainConfig['contracts']['withdraw']['contract_address']);
        }

        if ($blockchainConfig['token_distribution_event_address']) {
            $this->contracts['token_distribution_event'] = Contracts\OpspotTokenSaleEvent::at($blockchainConfig['contracts']['token_sale_event']['contract_address']);
        }
    }

    public function getContract($contract)
    {
        if (isset($this->contracts[$contract])) {
            return $this->contracts[$contract];
        }
        return null;
    }

    public function getPublicSettings()
    {
        $blockchainConfig = $this->config->get('blockchain') ?: [];

        return array_merge([
            'network_address' => $this->config->get('site_url') . self::$infuraProxyEndpoint,
            'client_network' => $blockchainConfig['client_network'],
            'wallet_address' => $blockchainConfig['wallet_address'],
            'boost_wallet_address' => $blockchainConfig['contracts']['boost']['wallet_address'],
            'token_distribution_event_address' => $blockchainConfig['contracts']['token_sale_event']['contract_address'],
            'rate' => $blockchainConfig['eth_rate'],
            'plus_address' => $blockchainConfig['contracts']['wire']['plus_address'],
            'default_gas_price' => $blockchainConfig['default_gas_price'],
            'overrides' => $this->getOverrides(),
        ], $this->contracts);
    }

    public function getOverrides()
    {
        $baseConfig = $this->config->get('blockchain') ?: [];
        $overrides = $this->config->get('blockchain_override') ?: [];
        $result = [];

        foreach ($overrides as $key => $override) {
            $blockchainConfig = array_merge($baseConfig, $override);

            $result[$key] = [
                'network_address' => $blockchainConfig['network_address'],
                'client_network' => $blockchainConfig['client_network'],
                'wallet_address' => $blockchainConfig['wallet_address'],
                'boost_wallet_address' => $blockchainConfig['contracts']['boost']['wallet_address'],
                'token_distribution_event_address' => $blockchainConfig['contracts']['token_sale_event']['contract_address'],
                'plus_address' => $blockchainConfig['contracts']['wire']['plus_address'],
                'default_gas_price' => $blockchainConfig['default_gas_price'],
            ];
        }

        return $result;
    }

    public function getRate()
    {
        // how many units per token
        return 1000;
    }
}
