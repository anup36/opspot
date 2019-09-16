<?php
/**
 * Prepared data interface
 */

namespace Opspot\Core\Data\Interfaces;

interface PreparedMethodInterface extends PreparedInterface
{
    /**
     * Sets the prepared method
     * @return string
     */
    public function getMethod();
}
