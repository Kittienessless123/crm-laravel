<?php

namespace App\Http\Requests\Seller;

use App\Concerns\SellerValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class SellerCreationRequest extends FormRequest
{
    use SellerValidationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->sellerRules(isUpdate: false);
    }
}