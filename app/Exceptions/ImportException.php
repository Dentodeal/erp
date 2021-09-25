<?php

namespace App\Exceptions;

use Exception;

class ImportException extends Exception
{
    protected $failures;
    public function __construct($failures){
        $this->failures = $failures;
    }

    public function failures()
    {
        return $this->failures;
    }
}
