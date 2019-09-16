<?php
/**
 * Twofactor required Exception
 */
namespace Opspot\Exceptions;

class TwoFactorRequired extends \Exception
{
    protected $code = "403";
}
