<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class RequestData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
        if (isset($data['durationInMilliseconds'])) {
            if (!is_int($data['durationInMilliseconds'])) {
                throw new TelemetryDataFormatException('"durationInMilliseconds" should be int type', $this->class);
            }
        }

        if (isset($data['httpResponseCode'])) {
            if (!is_int($data['httpResponseCode'])) {
                throw new TelemetryDataFormatException('"httpResponseCode" should be int type', $this->class);
            }
        }

        if (isset($data['isSuccessful'])) {
            if (!is_bool($data['isSuccessful'])) {
                throw new TelemetryDataFormatException('"isSuccessful" should be bool type', $this->class);
            }
        }
        if (isset($data['properties'])) {
            foreach ($data['properties'] as $ele) {
                if (is_array($ele)) {
                    throw new TelemetryDataFormatException('"properties" should be 1D array', $this->class);
                }
            }
        }
        if (isset($data['measurments'])) {
            foreach ($data['measurments'] as $ele) {
                if (is_array($ele)) {
                    throw new TelemetryDataFormatException('"measurments" should be 1D array', $this->class);
                }
                if (!is_float($ele)) {
                    throw new TelemetryDataFormatException('elements of "measurments" should be float type', $this->class);
                }
            }
        }
    }
}
