<?php

namespace Opspot\Core\Rewards;

use Opspot\Core\Data\ElasticSearch;
use Opspot\Core\Di\Di;
use Opspot\Entities\User;

class ReferralValidator
{
    /** @var Client $client */
    protected $client;

    /** @var string $hash */
    protected $hash;

    public function __construct($client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
    }

    /**
     * @param string $hash
     * @return ReferralValidator
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * Returns true if no user has referred using the provided hash
     * @return bool
     */
    public function validate()
    {
        $must = [
            [
                'match' => [
                    'action.keyword' => 'referral',
                ],
            ],
            [
                'match' => [
                    'user_phone_number_hash.keyword' => $this->hash,
                ],
            ]
        ];

        $query = [
            'index' => 'opspot-metrics-*',
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must,
                    ]
                ],
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        try {
            $result = $this->client->request($prepared);
        } catch (\Exception $e) {
            return false;
        }
        
        return count($result['hits']['hits']) === 0;
    }
}
