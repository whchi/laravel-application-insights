<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use ApplicationInsights\Channel\Contracts\Data_Point_Type;
use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

class MetricData extends CommonHelper
{
    protected function validateOptionalData(array $data): void
    {
        if (isset($data['properties'])) {
            foreach ($data['properties'] as $ele) {
                if (is_array($ele)) {
                    throw new TelemetryDataFormatException('"properties" should be 1D array', $this->class);
                }
            }
        }
        if (isset($data['type'])) {
            if (!is_int($data['type'])) {
                throw new TelemetryDataFormatException('"type" should be int type', $this->class);
            }
            if (!in_array(
                $data['type'], [
                    Data_Point_Type::Measurement,
                    Data_Point_Type::Aggregation,
                ]
            )
            ) {
                throw new TelemetryDataFormatException('"type" out of range', $this->class);
            }
        }
        if (isset($data['count'])) {
            if (!is_int($data['count'])) {
                throw new TelemetryDataFormatException('"count" should be int type', $this->class);
            }
        }

        if (isset($data['min'])) {
            if (!is_double($data['min'])) {
                throw new TelemetryDataFormatException('"min" should be double type', $this->class);
            }
        }

        if (isset($data['max'])) {
            if (!is_double($data['max'])) {
                throw new TelemetryDataFormatException('"max" should be double type', $this->class);
            }
        }

        if (isset($data['stdDev'])) {
            if (!is_double($data['stdDev'])) {
                throw new TelemetryDataFormatException('"stdDev" should be double type', $this->class);
            }
        }

    }
}
