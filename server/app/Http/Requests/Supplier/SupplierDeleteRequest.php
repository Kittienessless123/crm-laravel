<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class SupplierDeleteManyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1', 'max:100'],
            'ids.*' => ['required', 'string', 'exists:suppliers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Необходимо передать массив ID поставщиков',
            'ids.min' => 'Нужно указать хотя бы одного поставщика',
            'ids.max' => 'Нельзя удалить больше 100 поставщиков за раз',
            'ids.*.exists' => 'Поставщик с указанным ID не найден',
        ];
    }
}