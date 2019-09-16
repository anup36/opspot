<?php
/**
 * A opspot archive audio entity
 *
 */
namespace Opspot\Entities;

use cinemr;
use Opspot\Helpers;

// @todo: Check if it's OK we still extend Video
class Audio extends Video
{
    protected function initializeAttributes()
    {
        parent::initializeAttributes();

        $this->attributes['super_subtype'] = 'archive';
        $this->attributes['subtype'] = "audio";
    }
}
