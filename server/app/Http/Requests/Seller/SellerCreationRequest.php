<?php

namespace App\Http\Requests\Settings;

use App\Concerns\SellerValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SellerCreationRequest extends FormRequest
{
  use SellerValidationRules;

  public function rules(): array
  {
    return $this->sellerRules($this->seller()->id);
  }
  
}
