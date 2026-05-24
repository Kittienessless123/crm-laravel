<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'

interface Product {
  id: string
  product_name: string
  sku: string | null
  retail_price: number
  currency: string
  is_active: boolean
  category?: { name: string } | null
  supplier?: { name: string } | null
}

interface Props {
  products: { data: Product[]; links: any[] }
  categories: { id: string; name: string }[]
  filters: { search?: string; category_id?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()

const sortField = ref(props.filters?.sort_by || 'created_at')
const sortDir = ref(props.filters?.direction || 'desc')

const filters = reactive({
  search: props.filters?.search || '',
  category_id: props.filters?.category_id || '',
})

function fetchProducts() {
  router.get('/products', {
    search: filters.search || undefined,
    category_id: filters.category_id || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}

function sortBy(field: string) {
  sortDir.value = sortField.value === field ? (sortDir.value === 'asc' ? 'desc' : 'asc') : 'asc'
  sortField.value = field
  fetchProducts()
}

function goToPage(url: string) {
  const params = new URLSearchParams(url.split('?')[1])
  router.get('/products', {
    page: params.get('page') || undefined,
    search: filters.search || undefined,
    category_id: filters.category_id || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}

function resetFilters() {
  filters.search = ''
  filters.category_id = ''
  sortField.value = 'created_at'
  sortDir.value = 'desc'
  fetchProducts()
}

function deleteProduct(id: string) {
  if (confirm('Удалить товар?')) {
    router.delete(`/products/${id}`, { onSuccess: () => fetchProducts() })
  }
}
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Товары</h1>

    <div class="flex gap-3 mb-6 flex-wrap">
      <input v-model="filters.search" placeholder="Поиск..." @keyup.enter="fetchProducts"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <select v-model="filters.category_id" @change="fetchProducts"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white">
        <option value="">Все категории</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
      </select>
      <button @click="fetchProducts" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
      <button @click="resetFilters" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Сбросить</button>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-800 text-gray-300">
            <th @click="sortBy('product_name')" class="cursor-pointer p-3 border border-gray-600 text-left">
              Название {{ sortField === 'product_name' ? (sortDir === 'asc' ? '↑' : '↓') : '' }}
            </th>
            <th class="p-3 border border-gray-600 text-left">SKU</th>
            <th @click="sortBy('retail_price')" class="cursor-pointer p-3 border border-gray-600 text-left">
              Цена {{ sortField === 'retail_price' ? (sortDir === 'asc' ? '↑' : '↓') : '' }}
            </th>
            <th class="p-3 border border-gray-600 text-left">Категория</th>
            <th class="p-3 border border-gray-600 text-left">Поставщик</th>
            <th class="p-3 border border-gray-600 text-left">Статус</th>
            <th class="p-3 border border-gray-600 text-left">Действия</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-800 text-gray-300">
            <td class="p-3 border border-gray-600">
              <Link :href="`/products/${product.id}`" class="text-blue-400 hover:underline">
                {{ product.product_name }}
              </Link>
            </td>
            <td class="p-3 border border-gray-600">{{ product.sku || '—' }}</td>
            <td class="p-3 border border-gray-600">{{ product.retail_price }} {{ product.currency }}</td>
            <td class="p-3 border border-gray-600">{{ product.category?.name || '—' }}</td>
            <td class="p-3 border border-gray-600">{{ product.supplier?.name || '—' }}</td>
            <td class="p-3 border border-gray-600">
              <span :class="product.is_active ? 'text-green-400' : 'text-red-400'">
                {{ product.is_active ? 'Активен' : 'Неактивен' }}
              </span>
            </td>
            <td class="p-3 border border-gray-600">
              <Link :href="`/products/${product.id}`" class="mr-3 text-blue-400">👁️</Link>
              <button @click="deleteProduct(product.id)" class="text-red-400 hover:text-red-300">🗑️</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="mt-6 flex gap-2 flex-wrap">
      <button v-for="link in products.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700',
          !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]" />
    </div>
  </div>
</template>