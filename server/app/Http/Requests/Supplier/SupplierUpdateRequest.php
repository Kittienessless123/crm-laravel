<?php

namespace App\Http\Requests\Settings;

use App\Concerns\SupplierValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
{
  use SupplierValidationRules;

  public function rules(): array
  {
    return $this->supplierRules($this->supplier()->id);
  }
  
}
