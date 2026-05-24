<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface PriceList {
  id: string
  retail_price: number
  wholesale_price: number | null
  currency: string
  is_active: boolean
  product?: { id: string; product_name: string; sku: string } | null
  supplier?: { id: string; name: string } | null
}

interface Props {
  priceLists: { data: PriceList[]; links: any[] }
  filters: { search?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()
const sortField = ref(props.filters?.sort_by || 'created_at')
const sortDir = ref(props.filters?.direction || 'desc')
const search = ref(props.filters?.search || '')

function fetch() {
  router.get('/pricelist', {
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}

function sortBy(field: string) {
  sortDir.value = sortField.value === field ? (sortDir.value === 'asc' ? 'desc' : 'asc') : 'asc'
  sortField.value = field
  fetch()
}

function goToPage(url: string) {
  const params = new URLSearchParams(url.split('?')[1])
  router.get('/pricelist', {
    page: params.get('page') || undefined,
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Прайс-лист</h1>

    <div class="flex gap-3 mb-6">
      <input v-model="search" placeholder="Поиск..." @keyup.enter="fetch"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <button @click="fetch" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-800 text-gray-300">
          <th class="p-3 border border-gray-600">Товар</th>
          <th class="p-3 border border-gray-600">SKU</th>
          <th @click="sortBy('retail_price')" class="cursor-pointer p-3 border border-gray-600">Розница</th>
          <th class="p-3 border border-gray-600">Опт</th>
          <th class="p-3 border border-gray-600">Поставщик</th>
          <th class="p-3 border border-gray-600">Статус</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="pl in priceLists.data" :key="pl.id" class="hover:bg-gray-800 text-gray-300">
          <td class="p-3 border border-gray-600">
            <Link v-if="pl.product" :href="`/products/${pl.product.id}`" class="text-blue-400 hover:underline">
              {{ pl.product.product_name }}
            </Link>
            <span v-else class="text-gray-500">—</span>
          </td>
          <td class="p-3 border border-gray-600">{{ pl.product?.sku || '—' }}</td>
          <td class="p-3 border border-gray-600">{{ pl.retail_price }} {{ pl.currency }}</td>
          <td class="p-3 border border-gray-600">{{ pl.wholesale_price ? `${pl.wholesale_price} ${pl.currency}` : '—' }}</td>
          <td class="p-3 border border-gray-600">
            <Link v-if="pl.supplier" :href="`/suppliers/${pl.supplier.id}`" class="text-blue-400 hover:underline">
              {{ pl.supplier.name }}
            </Link>
            <span v-else class="text-gray-500">—</span>
          </td>
          <td class="p-3 border border-gray-600">
            <span :class="pl.is_active ? 'text-green-400' : 'text-red-400'">{{ pl.is_active ? 'Активен' : 'Неактивен' }}</span>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex gap-2">
      <button v-for="link in priceLists.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300',
          !link.url ? 'opacity-50 cursor-not-allowed' : ''
        ]" />
    </div>
  </div>
</template>