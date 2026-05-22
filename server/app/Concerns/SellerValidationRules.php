<?php

namespace App\Concerns;

use App\Models\Seller;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait SellerValidationRules
{
  protected function sellerRules(?int $sellerId = null): array
  {
    return [
      'name' => $this->nameRules(),
      'contact_info' => $this->contactInfoRules(),
    ];
  }

  protected function nameRules(): array
  {
    return ['required', 'string', 'max:255'];
  }

  protected function contactInfoRules(): array
  {
    return ['required', 'string', 'max:255'];
  }
}
