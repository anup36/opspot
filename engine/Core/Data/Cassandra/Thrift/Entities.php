<?php
/**
 * Cassandra entities wrapper
 */
namespace Opspot\Core\Data\Cassandra\Thrift;

class Entities
{
    protected $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
}
