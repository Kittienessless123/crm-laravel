<?php

namespace App\Http\Requests\Settings;

use App\Concerns\ProductValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreationRequest extends FormRequest
{
  use ProductValidationRules;

  public function rules(): array
  {
    return $this->productRules($this->product()->id);
  }
  
}
