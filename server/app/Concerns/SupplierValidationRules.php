<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait SupplierValidationRules
{
    /**
     * Главный метод с флагом isUpdate
     */
    protected function supplierRules(?string $supplierId = null, bool $isUpdate = false): array
    {
        return [
            'product_id' => $this->productIdRules($supplierId, $isUpdate),
            'name' => $this->nameRules($isUpdate),
        ];
    }

    /**
     * ========================================
     * ПРАВИЛА ДЛЯ КАЖДОГО ПОЛЯ
     * ========================================
     */

    protected function productIdRules(?string $supplierId = null, bool $isUpdate = false): array
    {
        $rules = [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'];

        if ($supplierId) {
            $rules[] = Rule::unique('suppliers', 'product_id')->ignore($supplierId);
        } else {
            $rules[] = 'unique:suppliers,product_id';
        }

        return $rules;
    }

    protected function nameRules(bool $isUpdate = false): array
    {
        return [
            $isUpdate ? 'sometimes' : 'required',
            'string',
            'min:2',
            'max:255',
        ];
    }

    /**
     * ========================================
     * СООБЩЕНИЯ ОБ ОШИБКАХ
     * ========================================
     */

    public function messages(): array
    {
        return [
            // Общие
            'required' => 'Поле :attribute обязательно для заполнения',
            'string' => 'Поле :attribute должно быть строкой',
            'max' => 'Поле :attribute не должно превышать :max символов',
            'min' => 'Поле :attribute должно быть не менее :min символов',
            'unique' => 'Такой :attribute уже существует',

            // Специфичные для полей
            'product_id.required' => 'Внешний ID поставщика обязателен',
            'product_id.unique' => 'Поставщик с таким внешним ID уже существует',
            'product_id.max' => 'Внешний ID поставщика слишком длинный (максимум 255 символов)',

            'name.required' => 'Название поставщика обязательно',
            'name.min' => 'Название поставщика должно быть не короче 2 символов',
            'name.max' => 'Название поставщика слишком длинное (максимум 255 символов)',
        ];
    }

    /**
     * ========================================
     * ЧЕЛОВЕЧЕСКИЕ НАЗВАНИЯ АТРИБУТОВ
     * ========================================
     */

    public function attributes(): array
    {
        return [
            'product_id' => 'внешний ID поставщика',
            'name' => 'название поставщика',
        ];
    }

    /**
     * ========================================
     * ПОДГОТОВКА ДАННЫХ ПЕРЕД ВАЛИДАЦИЕЙ
     * ========================================
     */

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge(['name' => trim($this->input('name'))]);
        }

        if ($this->has('product_id')) {
            $this->merge(['product_id' => trim($this->input('product_id'))]);
        }
    }
}