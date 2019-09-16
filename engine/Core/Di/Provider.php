<?php
/**
 * Opspot Dependency Injection
 */
namespace Opspot\Core\Di;

class Provider
{
    protected $di;

    public function __construct()
    {
        $this->di = Di::_();
    }
}
