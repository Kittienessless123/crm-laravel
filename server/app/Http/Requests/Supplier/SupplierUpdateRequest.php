<?php

namespace App\Http\Requests\Supplier;

use App\Concerns\SupplierValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
{
    use SupplierValidationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->supplierRules(
            supplierId: $this->route('supplier'),
            isUpdate: true
        );
    }
}