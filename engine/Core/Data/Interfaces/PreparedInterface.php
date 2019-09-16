<?php
/**
 * Prepared data interface
 */

namespace Opspot\Core\Data\Interfaces;

interface PreparedInterface
{
    /**
     * Build the prepared request
     * @return array
     */
    public function build();

    /**
     * Return options for the query
     */
    public function getOpts();
}
