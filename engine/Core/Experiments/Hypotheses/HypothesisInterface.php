<?php
/**
 * 
 */
namespace Opspot\Core\Experiments\Hypotheses;

use Opspot\Core\Experiments\Bucket;

interface HypothesisInterface
{

    /**
     * Return the id for the hypothesis
     * @return string
     */
    public function getId();

    /**
     * Return the buckets for the hypothesis
     * @return Bucket[]
     */
    public function getBuckets();

}
