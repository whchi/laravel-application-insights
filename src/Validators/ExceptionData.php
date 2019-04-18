<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use Whchi\LaravelApplicationInsights\Contracts\Validator;
use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class ExceptionData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
        if (isset($data['measurments'])) {
            array_walk(
                $data['measurments'], function ($ele) {
                    if (is_array($ele)) {
                        throw new TelemetryDataFormatException('"measurments" should be 1D array', $this->class);
                    }
                    if (!is_float($ele)) {
                        throw new TelemetryDataFormatException('elements of "measurments" should be float type', $this->class);
                    }
                }
            );
        }

        if (isset($data['properties'])) {
            array_walk(
                $data['properties'], function ($ele) {
                    if (is_array($ele)) {
                        throw new TelemetryDataFormatException('"properties" should be 1D array', $this->class);
                    }
                }
            );
        }
    }
}
