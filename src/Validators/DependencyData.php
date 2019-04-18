<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class DependencyData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
        if (isset($data['properties'])) {
            array_walk(
                $data['properties'], function ($ele) {
                    if (is_array($ele)) {
                        throw new TelemetryDataFormatException('"properties" should be 1D array', $this->class);
                    }
                }
            );
        }

        if (isset($data['resultCode'])) {
            if (!is_int($data['resultCode'])) {
                throw new TelemetryDataFormatException('"resultCode" should be int type', $this->class);
            }
        }

        if (isset($data['isSuccessful'])) {
            if (!is_bool($data['isSuccessful'])) {
                throw new TelemetryDataFormatException('"isSuccessful" should be bool type', $this->class);
            }
        }

        if (isset($data['durationInMilliseconds'])) {
            if (!is_int($data['durationInMilliseconds'])) {
                throw new TelemetryDataFormatException('"durationInMilliseconds" should be int type', $this->class);
            }
        }
    }
}
