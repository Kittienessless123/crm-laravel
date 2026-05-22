<?php

namespace App\Concerns;

use App\Models\Supplier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait SupplierValidationRules
{
  protected function supplierRules(): array
  {
    return [
      'name' => $this->nameRules(),

    ];
  }

  protected function nameRules(): array
  {
    return ['required', 'string', 'max:255'];
  }
}
