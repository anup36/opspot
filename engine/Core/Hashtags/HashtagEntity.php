<?php
/**
 * Hashtag Entity
 */

namespace Opspot\Core\Hashtags;

use Opspot\Traits\MagicAttributes;

/**
 * Class HashtagEntity
 * @package Opspot\Core\Hashtags
 * @method int getGuid()
 * @method HashtagEntity setGuid(int $value)
 * @method string getHashtag()
 * @method HashtagEntity setHashtag(string $value)
 */
class HashtagEntity
{
    use MagicAttributes;

    /** @var int $guid */
    private $guid;

    /** @var string $hashtag */
    private $hashtag;

}
