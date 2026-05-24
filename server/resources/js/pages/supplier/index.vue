<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Supplier {
  id: string
  name: string
  product_id: string | null
  region: string | null
  city: string | null
  contact_info: string | null
  is_active: boolean
}

interface Props {
  suppliers: { data: Supplier[]; links: any[] }
  filters: { search?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()

const sortField = ref(props.filters?.sort_by || 'created_at')
const sortDir = ref(props.filters?.direction || 'desc')
const search = ref(props.filters?.search || '')

function fetch() {
  router.get('/suppliers', {
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
  router.get('/suppliers', {
    page: params.get('page') || undefined,
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}

function deleteSupplier(id: string) {
  if (confirm('Удалить поставщика?')) {
    router.delete(`/suppliers/${id}`, { onSuccess: () => fetch() })
  }
}
</script>

<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Поставщики</h1>

    <div class="flex gap-3 mb-6">
      <input v-model="search" placeholder="Поиск..." @keyup.enter="fetch"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <button @click="fetch" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-800 text-gray-300">
          <th @click="sortBy('name')" class="cursor-pointer p-3 border border-gray-600">Название</th>
          <th class="p-3 border border-gray-600">Внешний ID</th>
          <th class="p-3 border border-gray-600">Регион</th>
          <th class="p-3 border border-gray-600">Город</th>
          <th class="p-3 border border-gray-600">Контакты</th>
          <th class="p-3 border border-gray-600">Статус</th>
          <th class="p-3 border border-gray-600"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in suppliers.data" :key="s.id" class="hover:bg-gray-800 text-gray-300">
          <td class="p-3 border border-gray-600">
            <Link :href="`/suppliers/${s.id}`" class="text-blue-400 hover:underline">{{ s.name }}</Link>
          </td>
          <td class="p-3 border border-gray-600">{{ s.product_id || '—' }}</td>
          <td class="p-3 border border-gray-600">{{ s.region || '—' }}</td>
          <td class="p-3 border border-gray-600">{{ s.city || '—' }}</td>
          <td class="p-3 border border-gray-600">{{ s.contact_info || '—' }}</td>
          <td class="p-3 border border-gray-600">
            <span :class="s.is_active ? 'text-green-400' : 'text-red-400'">{{ s.is_active ? 'Активен' : 'Неактивен' }}</span>
          </td>
          <td class="p-3 border border-gray-600">
            <Link :href="`/suppliers/${s.id}`" class="mr-3 text-blue-400">👁️</Link>
            <button @click="deleteSupplier(s.id)" class="text-red-400">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex gap-2">
      <button v-for="link in suppliers.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300',
          !link.url ? 'opacity-50 cursor-not-allowed' : ''
        ]" />
    </div>
  </div>
</template>