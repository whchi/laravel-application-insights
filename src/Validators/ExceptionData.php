<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class ExceptionData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
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

        if (isset($data['properties'])) {
            foreach ($data['properties'] as $ele) {
                if (is_array($ele)) {
                    throw new TelemetryDataFormatException('"properties" should be 1D array', $this->class);
                }
            }
        }
    }
}
