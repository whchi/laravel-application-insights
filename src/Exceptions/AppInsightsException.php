<?php

namespace Whchi\LaravelApplicationInsights\Exceptions;

use Exception;

class AppInsightsException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
