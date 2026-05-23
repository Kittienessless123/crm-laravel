<?php

namespace App\Http\Requests\Supplier;

use App\Concerns\SupplierValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierCreationRequest extends FormRequest
{
  use SupplierValidationRules;

  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return $this->supplierRules(isUpdate: false);
  }
}
