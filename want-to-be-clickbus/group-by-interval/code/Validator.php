<?php

namespace GroupByInterval;
use InvalidArgumentException;

class Validator
{
    public function isInteger($value)
    {
        return is_numeric($value) && !($value - intval($value));
    }

    public function validate(array $values, $validation)
    {
        if (!method_exists($this, $validation)) {
            throw new InvalidArgumentException('Invalid validation name', 1);
        }
        foreach ($values as $value) {
            if (!$this->{$validation}($value)) {
                return false;
            }
        }
        return true;
    }
}
