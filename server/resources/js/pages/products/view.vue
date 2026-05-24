<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Product {
  id: string
  product_name: string
  sku: string | null
  product_id: string | null
  description: string | null
  size: string | null
  base_price: number
  retail_price: number
  wholesale_price: number | null
  currency: string
  margin: number
  is_active: boolean
  is_available: boolean
  category?: { name: string } | null
  unit?: { name: string } | null
  seller?: { name: string } | null
  supplier?: { name: string } | null
}

defineProps<{ product: Product }>()
</script>

<template>
  <div class="p-6 max-w-3xl mx-auto text-gray-300">
    <Link href="/products" class="text-blue-400 hover:underline mb-4 inline-block">← Назад к списку</Link>
    <h1 class="text-2xl font-bold text-white mb-6">{{ product.product_name }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div v-for="(value, label) in {
        'SKU': product.sku,
        'Внешний ID': product.product_id,
        'Категория': product.category?.name,
        'Ед. измерения': product.unit?.name,
        'Базовая цена': `${product.base_price} ${product.currency}`,
        'Розничная цена': `${product.retail_price} ${product.currency}`,
        'Оптовая цена': product.wholesale_price ? `${product.wholesale_price} ${product.currency}` : null,
        'Наценка': `${product.margin}%`,
        'Продавец': product.seller?.name,
        'Поставщик': product.supplier?.name,
        'Размер': product.size,
      }" :key="label"
        class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">{{ label }}</div>
        <div class="text-white mt-1">{{ value || '—' }}</div>
      </div>

      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Статус</div>
        <span :class="product.is_active ? 'text-green-400' : 'text-red-400'">
          {{ product.is_active ? 'Активен' : 'Неактивен' }}
        </span>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">В наличии</div>
        <span :class="product.is_available ? 'text-green-400' : 'text-red-400'">
          {{ product.is_available ? 'Да' : 'Нет' }}
        </span>
      </div>
    </div>

    <div v-if="product.description" class="mt-6 bg-gray-800 p-4 rounded border border-gray-700">
      <div class="text-gray-400 text-sm mb-2">Описание</div>
      <p class="text-gray-300">{{ product.description }}</p>
    </div>
  </div>
</template>