<?php

namespace App\Http\Requests\Payment;

use App\Concerns\PaymentValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentDeleteRequest extends FormRequest
{
  use PaymentValidationRules;


  public function rules(): array
  {
    return [
      // Для одиночного удаления ID приходит в URL (не проверяется здесь)
    ];
  }
}
