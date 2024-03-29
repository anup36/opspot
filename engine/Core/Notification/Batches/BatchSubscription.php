<?php
/**
 * Notification BatchId
 */
namespace Opspot\Core\Notification\Batches;

use Opspot\Traits\MagicAttributes;

class BatchSubscription
{
    use MagicAttributes;

    /** @param int $userGuid */
    private $userGuid;

    /** @param string $batchId */
    private $batchId;

    /**
     * Export
     * @return array
     */
    public function export()
    {
        return [
            'userGuid' => $this->getUserGuid(),
            'batchId' => $this->getBatchId(),
        ];
    }

}
