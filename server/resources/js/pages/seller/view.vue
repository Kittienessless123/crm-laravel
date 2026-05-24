<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Seller {
  id: string
  name: string
  contact_info: string | null
  registration_date: string
  is_active: boolean
  description: string | null
}

defineProps<{ seller: Seller }>()
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto text-gray-300">
    <Link href="/sellers" class="text-blue-400 hover:underline mb-4 inline-block">← Назад к списку</Link>
    <h1 class="text-2xl font-bold text-white mb-6">{{ seller.name }}</h1>

    <div class="grid grid-cols-2 gap-4">
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Контакты</div>
        <div class="text-white mt-1">{{ seller.contact_info || '—' }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Дата регистрации</div>
        <div class="text-white mt-1">{{ new Date(seller.registration_date).toLocaleDateString() }}</div>
      </div>
      <div class="bg-gray-800 p-4 rounded border border-gray-700">
        <div class="text-gray-400 text-sm">Статус</div>
        <span :class="seller.is_active ? 'text-green-400' : 'text-red-400'">
          {{ seller.is_active ? 'Активен' : 'Неактивен' }}
        </span>
      </div>
    </div>

    <div v-if="seller.description" class="mt-6 bg-gray-800 p-4 rounded border border-gray-700">
      <div class="text-gray-400 text-sm mb-2">Описание</div>
      <p>{{ seller.description }}</p>
    </div>
  </div>
</template>