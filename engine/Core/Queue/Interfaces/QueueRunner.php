<?php
namespace Opspot\Core\Queue\Interfaces;

/**
 * Queue runner interface
 */
interface QueueRunner
{
    /**
     * Run the queue
     * @return void
     */
    public function run();
};
