<?php

namespace App\Concerns;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProductValidationRules
{
  protected function productRules(?int $productId = null): array
  {
    return [
      'name' => $this->nameRules(),
      'sku' => $this->skuRules(),
      'description' => $this->descriptionRules(),
      'category' => $this->categoryRules(),

    ];
  }

  protected function nameRules(): array
  {
    return ['required', 'string', 'max:255'];
  }

  protected function skuRules(): array
  {
    return ['required', 'string', 'max:255'];
  }

  protected function descriptionRules(): array
  {
    return ['required', 'string', 'max:255'];
  }

  protected function categoryRules(): array
  {
    return ['required', 'string', 'max:255'];
  }
}
