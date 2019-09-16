<?php
/**
 * Rates Services Interface
 *
 * @author emi
 */

namespace Opspot\Core\Blockchain\Services;


interface RatesInterface
{
    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @return float
     */
    public function get();
}
