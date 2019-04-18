<?php

namespace Whchi\LaravelApplicationInsights\Exceptions;

class TelemetryDataFormatException extends AppInsightsException
{
    public function __construct(string $message, string $calledClass)
    {
        parent::__construct('Set ' . $calledClass . ' error: ' . $message);
    }
}
