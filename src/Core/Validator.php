<?php

namespace Sanjayojha\PhpRestApi\Core;

class Validator
{
    public function integer(mixed $value, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): bool
    {
        if (!is_numeric($value) || (int)$value != $value) {
            return false;
        }

        if ($value < $min || $value > $max) {
            return false;
        }

        return true;
    }

    public function string(mixed $value, int $min = 0, int $max = PHP_INT_MAX): bool
    {
        if (!is_string($value)) {
            return false;
        }

        if (strlen($value) < $min || strlen($value) > $max) {
            return false;
        }

        return true;
    }

    public function validArray(array $value, array $allowedValues): string
    {
        $missingKeys = array_diff($allowedValues, array_keys($value));

        if (!empty($missingKeys)) {
            return "Missing required parameter(s): " . implode(", ", $missingKeys);
        }

        return "";
    }
}
