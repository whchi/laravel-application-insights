<?php

namespace Whchi\LaravelApplicationInsights\Validators;

use Carbon\Carbon;
use Throwable;
use Whchi\LaravelApplicationInsights\Contracts\Validator;
use Whchi\LaravelApplicationInsights\Exceptions\TelemetryDataFormatException;

abstract class CommonHelper implements Validator
{
    protected $class;

    public function __construct()
    {
        $this->class = substr(strrchr(get_called_class(), '\\'), 1);
    }

    public function validate(array $data, array $required): void
    {
        $this->validateRequired($data, array_keys($required));
        array_walk(
            $required, function ($ele, $idx) use ($data) {
                $this->validateRequiredByType($data[$idx], $ele);
            }
        );
        $this->validateOptionalData($data);
    }

    abstract protected function validateOptionalData(array $data): void;

    private function validateRequiredByType($data, string $type): void
    {
        if ($type === 'Carbon') {
            if (!$data instanceof Carbon) {
                throw new TelemetryDataFormatException('variable should be Carbon type', $this->class);
            }
        } else if ($type === 'uri') {
            if (!filter_var($data, FILTER_VALIDATE_URL)) {
                throw new TelemetryDataFormatException('variable should be a valid uri', $this->class);
            }
        } else if ($type === 'Throwable') {
            if (!$data instanceof Throwable) {
                throw new TelemetryDataFormatException('variable should be Throwable type', $this->class);
            }
        } else if ($type !== gettype($data)) {
            throw new TelemetryDataFormatException('variable type error, "' . gettype($data) . '" is not "' . $type . '"', $this->class);
        }
    }

    private function validateRequired(array $data, array $required): void
    {
        if (!array_has($data, $required)) {
            throw new TelemetryDataFormatException('field "' . implode('","', $required) . '" are required', $this->class);
        }
    }
}
