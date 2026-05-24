<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Seller {
  id: string
  name: string
  contact_info: string | null
  registration_date: string
  is_active: boolean
}

interface Props {
  sellers: { data: Seller[]; links: any[] }
  filters: { search?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()

const sortField = ref(props.filters?.sort_by || 'creation_date')
const sortDir = ref(props.filters?.direction || 'desc')
const search = ref(props.filters?.search || '')

function fetch() {
  router.get('/sellers', {
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
  router.get('/sellers', {
    page: params.get('page') || undefined,
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}

function deleteSeller(id: string) {
  if (confirm('Удалить продавца?')) {
    router.delete(`/sellers/${id}`, { onSuccess: () => fetch() })
  }
}
</script>

<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Продавцы</h1>

    <div class="flex gap-3 mb-6">
      <input v-model="search" placeholder="Поиск..." @keyup.enter="fetch"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <button @click="fetch" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-800 text-gray-300">
          <th @click="sortBy('name')" class="cursor-pointer p-3 border border-gray-600">Имя</th>
          <th class="p-3 border border-gray-600">Контакты</th>
          <th @click="sortBy('registration_date')" class="cursor-pointer p-3 border border-gray-600">Дата регистрации</th>
          <th class="p-3 border border-gray-600">Статус</th>
          <th class="p-3 border border-gray-600"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="s in sellers.data" :key="s.id" class="hover:bg-gray-800 text-gray-300">
          <td class="p-3 border border-gray-600">
            <Link :href="`/sellers/${s.id}`" class="text-blue-400 hover:underline">{{ s.name }}</Link>
          </td>
          <td class="p-3 border border-gray-600">{{ s.contact_info || '—' }}</td>
          <td class="p-3 border border-gray-600">{{ new Date(s.registration_date).toLocaleDateString() }}</td>
          <td class="p-3 border border-gray-600">
            <span :class="s.is_active ? 'text-green-400' : 'text-red-400'">{{ s.is_active ? 'Активен' : 'Неактивен' }}</span>
          </td>
          <td class="p-3 border border-gray-600">
            <Link :href="`/sellers/${s.id}`" class="mr-3 text-blue-400">👁️</Link>
            <button @click="deleteSeller(s.id)" class="text-red-400">🗑️</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex gap-2">
      <button v-for="link in sellers.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300',
          !link.url ? 'opacity-50 cursor-not-allowed' : ''
        ]" />
    </div>
  </div>
</template>