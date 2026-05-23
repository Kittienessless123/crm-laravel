<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait SellerValidationRules
{
    /**
     * Главный метод с флагом isUpdate
     */
    protected function sellerRules(?string $sellerId = null, bool $isUpdate = false): array
    {
        return [
            'name' => $this->nameRules($isUpdate),
            'contact_info' => $this->contactInfoRules($isUpdate),
            'registration_date' => $this->registrationDateRules($isUpdate),
        ];
    }

    /**
     * ========================================
     * ПРАВИЛА ДЛЯ КАЖДОГО ПОЛЯ
     * ========================================
     */

    protected function nameRules(bool $isUpdate = false): array
    {
        return [
            $isUpdate ? 'sometimes' : 'required',
            'string',
            'min:2',
            'max:255',
        ];
    }

    protected function contactInfoRules(bool $isUpdate = false): array
    {
        return [
            $isUpdate ? 'sometimes|nullable' : 'nullable',
            'string',
            'max:500',
        ];
    }

    protected function registrationDateRules(bool $isUpdate = false): array
    {
        return [
            $isUpdate ? 'sometimes|nullable' : 'nullable',
            'date',
            'date_format:Y-m-d',
            'before_or_equal:today',
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
            'date' => 'Поле :attribute должно быть датой',
            'date_format' => 'Поле :attribute должно быть в формате Y-m-d',
            'before_or_equal' => 'Поле :attribute не может быть позже сегодняшнего дня',

            // Специфичные для полей
            'name.required' => 'Название продавца обязательно',
            'name.min' => 'Название продавца должно быть не короче 2 символов',
            'name.max' => 'Название продавца слишком длинное (максимум 255 символов)',

            'contact_info.max' => 'Контактная информация слишком длинная (максимум 500 символов)',

            'registration_date.date' => 'Дата регистрации должна быть валидной датой',
            'registration_date.date_format' => 'Дата регистрации должна быть в формате ГГГГ-ММ-ДД',
            'registration_date.before_or_equal' => 'Дата регистрации не может быть в будущем',
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
            'name' => 'название продавца',
            'contact_info' => 'контактная информация',
            'registration_date' => 'дата регистрации',
        ];
    }

    /**
     * ========================================
     * ПОДГОТОВКА ДАННЫХ ПЕРЕД ВАЛИДАЦИЕЙ
     * ========================================
     */

    protected function prepareForValidation(): void
    {
        // Тримминг строк
        if ($this->has('name')) {
            $this->merge(['name' => trim($this->input('name'))]);
        }

        if ($this->has('contact_info')) {
            $this->merge(['contact_info' => trim($this->input('contact_info'))]);
        }
    }
}