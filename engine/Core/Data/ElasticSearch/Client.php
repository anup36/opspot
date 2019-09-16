<?php

/**
 * ElasticSearch Client
 *
 * @author emi
 */

namespace Opspot\Core\Data\ElasticSearch;

use Elasticsearch;

use Opspot\Core\Data\Interfaces;
use Opspot\Core\Di\Di;

class Client implements Interfaces\ClientInterface
{
    /** @var Elasticsearch\Client $elasticsearch */
    protected $elasticsearch;

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $hosts = Di::_()->get('Config')->elasticsearch['hosts'];

        $this->elasticsearch = Elasticsearch\ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    /**
     * @param Interfaces\PreparedMethodInterface $query
     * @return mixed
     */
    public function request(Interfaces\PreparedMethodInterface $query)
    {
        return $this->elasticsearch->{$query->getMethod()}($query->build());
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function bulk($params = [])
    {
        return $this->elasticsearch->bulk($params);
    }

    /**
     * @return Elasticsearch\Client
     */
    public function getClient()
    {
        return $this->elasticsearch;
    }
}
