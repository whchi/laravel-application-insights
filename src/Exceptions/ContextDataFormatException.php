<?php

namespace Whchi\LaravelApplicationInsights\Exceptions;

class ContextDataFormatException extends AppInsightsException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
