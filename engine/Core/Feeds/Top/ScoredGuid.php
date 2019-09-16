<?php
/**
 * ScoredGuid
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Opspot\Core\Feeds\Top;


use Opspot\Traits\MagicAttributes;

/**
 * Class ScoredGuid
 * @package Opspot\Core\Feeds\Top
 * @method int getGuid()
 * @method ScoredGuid setGuid(int $guid)
 * @method float getScore()
 */
class ScoredGuid
{
    use MagicAttributes;

    /** @var int */
    protected $guid;

    /** @var float */
    protected $score;

    /**
     * @param $score
     * @return $this
     */
    public function setScore($score)
    {
        $this->score = (float) $score;
        return $this;
    }
}
