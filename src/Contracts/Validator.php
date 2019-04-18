<?php

namespace Whchi\LaravelApplicationInsights\Contracts;

interface Validator
{
    /**
     * Check data format is valid or not
     *
     * @return void
     */
    public function validate(array $data, array $required): void;
}
