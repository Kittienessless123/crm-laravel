<?php

namespace App\Http\Requests\Product;

use App\Concerns\ProductValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductDeleteRequest extends FormRequest
{
  use ProductValidationRules;


  public function rules(): array
  {
    return [
      // Для одиночного удаления ID приходит в URL (не проверяется здесь)
    ];
  }
}
