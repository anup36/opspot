<?php

namespace Opspot\Core\Security\RateLimits\Aggregates;

use Opspot\Core\Data\ElasticSearch;
use Opspot\Core\Trending\Aggregates\Aggregate;

class Reminds extends Aggregate
{
    public function get()
    {
        $cardinality_field = 'user_phone_number_hash';

        $filter = [
            'term' => ['action' => 'remind']
        ];

        $must = [
            [
                'range' => [
                    '@timestamp' => [
                        'gte' => $this->from,
                        'lte' => $this->to
                    ]
                ]
            ]
        ];

        $query = [
            'index' => 'opspot-metrics-*',
            'type' => 'action',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => $filter,
                        'must' => $must
                    ]
                ],
                'aggs' => [
                    'entities' => [
                        'terms' => [
                            'field' => "user_guid.keyword",
                            'size' => $this->limit,
                        ],
                    ]
                ]
            ]
        ];

        $prepared = new ElasticSearch\Prepared\Search();
        $prepared->query($query);

        $result = $this->client->request($prepared);

        $entities = [];
        foreach ($result['aggregations']['entities']['buckets'] as $entity) {
            $entities[$entity['key']] = $entity['doc_count'];
        }
        return $entities;
    }
}
