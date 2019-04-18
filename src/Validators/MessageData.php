<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use ApplicationInsights\Channel\Contracts\Message_Severity_Level;
use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class MessageData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
        if (isset($data['properties'])) {
            array_walk(
                $data['properties'], function ($ele) {
                    if (is_array($ele)) {
                        throw new TelemetryDataFormatException('"properties" should be 1D array',  $this->class);
                    }
                }
            );
        }
        if (isset($data['level'])) {
            if (!is_int($data['level'])) {
                throw new TelemetryDataFormatException('"level" should be int level',  $this->class);
            }
            if (!in_array(
                $data['level'], [
                Message_Severity_Level::VERBOSE,
                Message_Severity_Level::INFORMATION,
                Message_Severity_Level::WARNING,
                Message_Severity_Level::ERROR,
                Message_Severity_Level::CRITICAL]
            )
            ) {
                throw new TelemetryDataFormatException('"level" out of range',  $this->class);
            }
        }
    }
}
