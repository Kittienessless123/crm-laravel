<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

interface Payment {
  id: string
  amount: number
  payment_method: string
  payment_date: string
  status: string
  transaction?: { id: string; transaction_number: string } | null
}

interface Props {
  payments: { data: Payment[]; links: any[] }
  filters: { search?: string; sort_by?: string; direction?: string }
}

const props = defineProps<Props>()
const sortField = ref(props.filters?.sort_by || 'payment_date')
const sortDir = ref(props.filters?.direction || 'desc')
const search = ref(props.filters?.search || '')

function fetch() {
  router.get('/payments', {
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
  router.get('/payments', {
    page: params.get('page') || undefined,
    search: search.value || undefined,
    sort_by: sortField.value,
    direction: sortDir.value,
  }, { preserveState: true, replace: true })
}
</script>

<template>
  <div class="p-6 max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-white mb-6">Платежи</h1>

    <div class="flex gap-3 mb-6">
      <input v-model="search" placeholder="Поиск..." @keyup.enter="fetch"
        class="px-3 py-2 bg-gray-800 border border-gray-600 rounded text-white placeholder-gray-400 w-64" />
      <button @click="fetch" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Найти</button>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-800 text-gray-300">
          <th @click="sortBy('amount')" class="cursor-pointer p-3 border border-gray-600">Сумма</th>
          <th class="p-3 border border-gray-600">Метод</th>
          <th @click="sortBy('payment_date')" class="cursor-pointer p-3 border border-gray-600">Дата</th>
          <th @click="sortBy('status')" class="cursor-pointer p-3 border border-gray-600">Статус</th>
          <th class="p-3 border border-gray-600">Транзакция</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in payments.data" :key="p.id" class="hover:bg-gray-800 text-gray-300">
          <td class="p-3 border border-gray-600">{{ p.amount }}</td>
          <td class="p-3 border border-gray-600">{{ p.payment_method }}</td>
          <td class="p-3 border border-gray-600">{{ new Date(p.payment_date).toLocaleDateString() }}</td>
          <td class="p-3 border border-gray-600">
            <span :class="p.status === 'completed' ? 'text-green-400' : 'text-yellow-400'">{{ p.status }}</span>
          </td>
          <td class="p-3 border border-gray-600">
            <Link v-if="p.transaction" :href="`/transactions/${p.transaction.id}`" class="text-blue-400 hover:underline">
              {{ p.transaction.transaction_number }}
            </Link>
            <span v-else class="text-gray-500">—</span>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex gap-2">
      <button v-for="link in payments.links" :key="link.label" v-html="link.label" :disabled="!link.url"
        @click="link.url && goToPage(link.url)" :class="[
          'px-3 py-2 rounded border border-gray-600 text-sm',
          link.active ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-300',
          !link.url ? 'opacity-50 cursor-not-allowed' : ''
        ]" />
    </div>
  </div>
</template>