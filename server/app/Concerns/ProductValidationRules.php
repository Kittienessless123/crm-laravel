<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait ProductValidationRules
{
  /**
   * Главный метод — теперь с флагом isUpdate
   */
  protected function productRules(?string $productId = null, bool $isUpdate = false): array
  {
    $required = $isUpdate ? 'sometimes' : 'required';
    $nullable = $isUpdate ? 'sometimes|nullable' : 'nullable';

    return [
      'product_id' => $this->productIdRules($productId, $isUpdate),
      'product_name' => $this->productNameRules($isUpdate),
      'sku' => $this->skuRules($productId, $isUpdate),
      'description' => $this->descriptionRules($isUpdate),
      'category_id' => $this->categoryRules($isUpdate),
      'unit_id' => $this->unitRules($isUpdate),
      'size' => $this->sizeRules($isUpdate),
      'base_price' => $this->basePriceRules($isUpdate),
      'retail_price' => $this->retailPriceRules($isUpdate),
      'wholesale_price' => $this->wholesalePriceRules($isUpdate),
      'currency' => $this->currencyRules($isUpdate),
      'margin' => $this->marginRules($isUpdate),
      'photo_url' => $this->photoRules($isUpdate),
      'seller_id' => $this->sellerRules($isUpdate),
      'supplier_id' => $this->supplierRules($isUpdate),
      'is_active' => $this->booleanRules($isUpdate),
      'is_available' => $this->booleanRules($isUpdate),
      'availability_date' => $this->availabilityDateRules($isUpdate),
      'metadata' => $this->metadataRules($isUpdate),
    ];
  }

  /**
   * ========================================
   * ПРАВИЛА ДЛЯ КАЖДОГО ПОЛЯ
   * ========================================
   */

  protected function productIdRules(?string $productId = null, bool $isUpdate = false): array
  {
    $rules = [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'];

    if ($productId) {
      $rules[] = Rule::unique('products', 'product_id')->ignore($productId);
    } else {
      $rules[] = 'unique:products,product_id';
    }

    return $rules;
  }

  protected function productNameRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes' : 'required', 'string', 'min:2', 'max:255'];
  }

  protected function skuRules(?string $productId = null, bool $isUpdate = false): array
  {
    $rules = [$isUpdate ? 'sometimes' : 'required', 'string', 'max:255'];

    if ($productId) {
      $rules[] = Rule::unique('products', 'sku')->ignore($productId);
    } else {
      $rules[] = 'unique:products,sku';
    }

    return $rules;
  }

  protected function descriptionRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'string', 'max:5000'];
  }

  protected function categoryRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes' : 'required', 'string', 'exists:categories,id'];
  }

  protected function unitRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes' : 'required', 'string', 'exists:units,id'];
  }

  protected function sizeRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'string', 'max:100'];
  }

  protected function basePriceRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes' : 'required', 'numeric', 'min:0', 'max:999999999.99'];
  }

  protected function retailPriceRules(bool $isUpdate = false): array
  {
    return [
      $isUpdate ? 'sometimes' : 'required',
      'numeric',
      'min:0',
      'max:999999999.99',
      // Кастомная проверка: розничная цена >= базовой
      function ($attribute, $value, $fail) {
        $basePrice = request()->input('base_price');
        if ($basePrice && $value < $basePrice) {
          $fail('Розничная цена не может быть ниже базовой');
        }
      }
    ];
  }

  protected function wholesalePriceRules(bool $isUpdate = false): array
  {
    return [
      $isUpdate ? 'sometimes|nullable' : 'nullable',
      'numeric',
      'min:0',
      'max:999999999.99',
      function ($attribute, $value, $fail) {
        $basePrice = request()->input('base_price');
        if ($basePrice && $value < $basePrice) {
          $fail('Оптовая цена не может быть ниже базовой');
        }
      }
    ];
  }

  protected function currencyRules(bool $isUpdate = false): array
  {
    return [
      $isUpdate ? 'sometimes' : 'required',
      'string',
      Rule::in(['RUB', 'USD', 'EUR'])
    ];
  }

  protected function marginRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'numeric', 'min:0', 'max:100'];
  }

  protected function photoRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'url', 'max:2048'];
  }

  protected function sellerRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'string', 'exists:sellers,id'];
  }

  protected function supplierRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'string', 'exists:suppliers,id'];
  }

  protected function booleanRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes' : 'required', 'boolean'];
  }

  protected function availabilityDateRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'date', 'date_format:Y-m-d'];
  }

  protected function metadataRules(bool $isUpdate = false): array
  {
    return [$isUpdate ? 'sometimes|nullable' : 'nullable', 'json'];
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
      'unique' => 'Такой :attribute уже существует',
      'exists' => 'Указанный :attribute не найден',
      'numeric' => 'Поле :attribute должно быть числом',
      'boolean' => 'Поле :attribute должно быть true или false',
      'url' => 'Поле :attribute должно быть валидным URL',
      'json' => 'Поле :attribute должно быть валидным JSON',
      'in' => 'Поле :attribute должно быть одним из: :values',
      'min' => 'Поле :attribute должно быть не менее :min',
      'date' => 'Поле :attribute должно быть датой',
      'date_format' => 'Поле :attribute должно быть в формате Y-m-d',

      // Специфичные для полей
      'product_name.required' => 'Название товара обязательно',
      'product_name.min' => 'Название товара должно быть не короче 2 символов',
      'product_name.max' => 'Название товара слишком длинное (максимум 255 символов)',

      'sku.required' => 'Артикул обязателен',
      'sku.unique' => 'Товар с таким артикулом уже существует',

      'product_id.required' => 'Внешний ID товара обязателен',
      'product_id.unique' => 'Товар с таким внешним ID уже существует',

      'category_id.required' => 'Категория обязательна',
      'category_id.exists' => 'Выбранная категория не существует',

      'unit_id.required' => 'Единица измерения обязательна',
      'unit_id.exists' => 'Выбранная единица измерения не существует',

      'base_price.required' => 'Базовая цена обязательна',
      'base_price.numeric' => 'Базовая цена должна быть числом',
      'base_price.min' => 'Базовая цена не может быть отрицательной',
      'base_price.max' => 'Слишком большая базовая цена',

      'retail_price.required' => 'Розничная цена обязательна',
      'retail_price.numeric' => 'Розничная цена должна быть числом',
      'retail_price.min' => 'Розничная цена не может быть отрицательной',

      'currency.required' => 'Валюта обязательна',
      'currency.in' => 'Допустимые валюты: RUB, USD, EUR',

      'is_active.boolean' => 'Статус активности должен быть true или false',
      'is_available.boolean' => 'Статус наличия должен быть true или false',

      'photo_url.url' => 'Ссылка на фото должна быть валидным URL',
      'metadata.json' => 'Метаданные должны быть валидным JSON',

      'seller_id.exists' => 'Указанный продавец не найден',
      'supplier_id.exists' => 'Указанный поставщик не найден',

      'margin.min' => 'Наценка не может быть отрицательной',
      'margin.max' => 'Наценка не может превышать 100%',
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
      'product_id' => 'внешний ID товара',
      'product_name' => 'название товара',
      'sku' => 'артикул',
      'description' => 'описание',
      'category_id' => 'категория',
      'unit_id' => 'единица измерения',
      'size' => 'размер',
      'base_price' => 'базовая цена',
      'retail_price' => 'розничная цена',
      'wholesale_price' => 'оптовая цена',
      'currency' => 'валюта',
      'margin' => 'наценка',
      'photo_url' => 'ссылка на фото',
      'seller_id' => 'продавец',
      'supplier_id' => 'поставщик',
      'is_active' => 'статус активности',
      'is_available' => 'наличие',
      'availability_date' => 'дата доступности',
      'metadata' => 'метаданные',
    ];
  }

  /**
   * ========================================
   * ПОДГОТОВКА ДАННЫХ ПЕРЕД ВАЛИДАЦИЕЙ
   * ========================================
   */

  protected function prepareForValidation(): void
  {
    if ($this->has('sku')) {
      $this->merge(['sku' => strtoupper(trim($this->input('sku')))]);
    }

    if ($this->has('currency')) {
      $this->merge(['currency' => strtoupper($this->input('currency'))]);
    }

    if ($this->has('is_active')) {
      $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    if ($this->has('is_available')) {
      $this->merge(['is_available' => $this->boolean('is_available')]);
    }

    // Тримминг строк
    foreach (['product_name', 'description', 'size'] as $field) {
      if ($this->has($field) && is_string($this->$field)) {
        $this->merge([$field => trim($this->$field)]);
      }
    }
  }
}
