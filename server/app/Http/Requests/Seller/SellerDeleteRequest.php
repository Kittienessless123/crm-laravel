<?php

namespace App\Http\Requests\Seller;

use Illuminate\Foundation\Http\FormRequest;

class SellerDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ID из URL — валидируется через Route Model Binding
        ];
    }
}

class SellerDeleteManyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['required', 'string', 'exists:sellers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Необходимо передать массив ID продавцов',
            'ids.array' => 'ID должны быть переданы массивом',
            'ids.min' => 'Нужно указать хотя бы одного продавца',
            'ids.max' => 'Нельзя удалить больше 100 продавцов за раз',
            'ids.*.exists' => 'Продавец с указанным ID не найден',
        ];
    }
}