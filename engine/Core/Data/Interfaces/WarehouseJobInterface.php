<?php
/**
 * Warehouse Job Interface
 */

namespace Opspot\Core\Data\Interfaces;

interface WarehouseJobInterface
{
    public function run(array $slugs = array());
}
