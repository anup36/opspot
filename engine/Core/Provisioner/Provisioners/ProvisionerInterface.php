<?php
namespace Opspot\Core\Provisioner\Provisioners;

use Opspot\Core\Provisioner\Tasks\TaskInterface;

interface ProvisionerInterface
{
    public function provision(bool $cleanData);
}
