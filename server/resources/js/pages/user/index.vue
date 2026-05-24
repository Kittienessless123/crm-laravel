<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface User {
  id: string
  name: string
  email: string
  is_active: boolean
  created_at: string
}

interface Props {
  users: { data: User[]; links: any[] }
  filters: { search?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()
const sortField = ref(props.filters?.sort_by || 'created_at')
const sortDir = ref(props.filters?.direction || 'desc')
const search = ref(props.filters?.search || '')

function fetch() {
  router.get('/users', {
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
  router.get('/users', {
    page: params.get('page') || undefined,
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}
</script>

<template>
  <div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Пользователи</h1>

    <div class="flex gap-3 mb-6">
      <input v-model="search" placeholder="Поиск..." @keyup.enter="fetch"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <button @click="fetch" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-800 text-gray-300">
          <th @click="sortBy('name')" class="cursor-pointer p-3 border border-gray-600">Имя</th>
          <th @click="sortBy('email')" class="cursor-pointer p-3 border border-gray-600">Email</th>
          <th @click="sortBy('created_at')" class="cursor-pointer p-3 border border-gray-600">Создан</th>
          <th class="p-3 border border-gray-600">Статус</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="u in users.data" :key="u.id" class="hover:bg-gray-800 text-gray-300">
          <td class="p-3 border border-gray-600">{{ u.name }}</td>
          <td class="p-3 border border-gray-600">{{ u.email }}</td>
          <td class="p-3 border border-gray-600">{{ new Date(u.created_at).toLocaleDateString() }}</td>
          <td class="p-3 border border-gray-600">
            <span :class="u.is_active ? 'text-green-400' : 'text-red-400'">{{ u.is_active ? 'Активен' : 'Неактивен' }}</span>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex gap-2">
      <button v-for="link in users.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300',
          !link.url ? 'opacity-50 cursor-not-allowed' : ''
        ]" />
    </div>
  </div>
</template>