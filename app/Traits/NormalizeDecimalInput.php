<?php

namespace App\Traits;

trait NormalizeDecimalInput
{
    protected function normalizeDecimal(string $field): void
    {
        if ($this->has($field)) {
            $this->merge([
                $field => str_replace(',', '.', $this->$field),
            ]);
        }
    }
}
