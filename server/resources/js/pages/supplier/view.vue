<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Supplier {
  id: string
  name: string
  product_id: string | null
  region: string | null
  city: string | null
  contact_info: string | null
  is_active: boolean
}

defineProps<{ supplier: Supplier }>()
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto text-gray-300">
    <Link href="/suppliers" class="text-blue-400 hover:underline mb-4 inline-block">← Назад к списку</Link>
    <h1 class="text-2xl font-bold text-white mb-6">{{ supplier.name }}</h1>

    <div class="grid grid-cols-2 gap-4">
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Внешний ID</div>
        <div class="text-white mt-1">{{ supplier.product_id || '—' }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Регион</div>
        <div class="text-white mt-1">{{ supplier.region || '—' }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Город</div>
        <div class="text-white mt-1">{{ supplier.city || '—' }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Контакты</div>
        <div class="text-white mt-1">{{ supplier.contact_info || '—' }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Статус</div>
        <span :class="supplier.is_active ? 'text-green-400' : 'text-red-400'">
          {{ supplier.is_active ? 'Активен' : 'Неактивен' }}
        </span>
      </div>
    </div>
  </div>
</template>