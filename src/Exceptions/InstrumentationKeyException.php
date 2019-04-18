<?php

namespace Whchi\LaravelApplicationInsights\Exceptions;

class InstrumentationKeyException extends AppInsightsException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
