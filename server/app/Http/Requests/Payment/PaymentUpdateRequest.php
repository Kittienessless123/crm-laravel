<?php

namespace App\Http\Requests\Payment;

use App\Concerns\PaymentValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
{
  use PaymentValidationRules;

  public function rules(): array
  {
    return $this->paymentRules($this->payment()->id);
  }
  
}
