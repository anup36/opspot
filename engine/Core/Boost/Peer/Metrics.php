<?php

namespace Opspot\Core\Boost\Peer;


use Opspot\Core\Boost\Repository;
use Opspot\Core\Data;
use Opspot\Core\Di\Di;

class Metrics
{
    protected $mongo;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

}
