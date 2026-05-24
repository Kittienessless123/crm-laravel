<template>
    <div style="padding: 20px; max-width: 1200px; margin: 0 auto">
        <h1>Товары</h1>

        <!-- Поиск и фильтры -->
        <div style="margin-bottom: 20px; display: flex; gap: 10px">
            <input
                v-model="filters.search"
                placeholder="Поиск по названию или SKU..."
                @keyup.enter="fetchProducts"
                style="padding: 8px; width: 300px"
            />
            <select
                v-model="filters.category_id"
                @change="fetchProducts"
                style="padding: 8px"
            >
                <option value="">Все категории</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name}}
                </option>
            </select>
            <button @click="fetchProducts" style="padding: 8px 16px">
                Найти
            </button>
            <button @click="resetFilters" style="padding: 8px 16px">
                Сбросить
            </button>
        </div>

        <!-- Таблица -->
        <table style="width: 100%; border-collapse: collapse">
            <thead>
                <tr style="background: #f5f5f5">
                    <th
                        @click="sortBy('product_name')"
                        style="
                            cursor: pointer;
                            padding: 10px;
                            border: 1px solid #ddd;
                        "
                    >
                        Название
                        {{
                            sortField === 'product_name'
                                ? sortDir === 'asc'
                                    ? '↑'
                                    : '↓'
                                : ''
                        }}
                    </th>
                    <th style="padding: 10px; border: 1px solid #ddd">SKU</th>
                    <th
                        @click="sortBy('retail_price')"
                        style="
                            cursor: pointer;
                            padding: 10px;
                            border: 1px solid #ddd;
                        "
                    >
                        Цена
                        {{
                            sortField === 'retail_price'
                                ? sortDir === 'asc'
                                    ? '↑'
                                    : '↓'
                                : ''
                        }}
                    </th>
                    <th style="padding: 10px; border: 1px solid #ddd">
                        Категория
                    </th>
                    <th style="padding: 10px; border: 1px solid #ddd">
                        Поставщик
                    </th>
                    <th style="padding: 10px; border: 1px solid #ddd">
                        Статус
                    </th>
                    <th style="padding: 10px; border: 1px solid #ddd">
                        Действия
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="product in products!.data"
                    :key="product.id"
                    style="border-bottom: 1px solid #ddd"
                >
                    <td style="padding: 10px; border: 1px solid #ddd">
                        <Link
                            :href="`/products/${product.id}`"
                            style="color: blue; text-decoration: underline"
                        >
                            {{ product.product_name }}
                        </Link>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        {{ product.sku }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        {{ product.retail_price }} {{ product.currency }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        {{ product.category?.name || '—' }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        {{ product.supplier?.name || '—' }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        <span
                            :style="{
                                color: product.is_active ? 'green' : 'red',
                            }"
                        >
                            {{ product.is_active ? 'Активен' : 'Неактивен' }}
                        </span>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd">
                        <Link
                            :href="`/products/${product.id}`"
                            style="margin-right: 10px"
                            >👁️</Link
                        >
                        <button
                            @click="deleteProduct(product.id)"
                            style="color: red"
                        >
                            🗑️
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Пагинация -->
        <div
            style="
                margin-top: 20px;
                display: flex;
                gap: 10px;
                align-items: center;
            "
        >
            <button
                v-for="link in products!.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="link.url && goToPage(link.url)"
                :style="{
                    padding: '8px 12px',
                    background: link.active ? '#007bff' : '#f0f0f0',
                    color: link.active ? 'white' : 'black',
                    border: '1px solid #ddd',
                    cursor: link.url ? 'pointer' : 'default',
                }"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ref, reactive, onMounted } from 'vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const sortField = ref(props.filters?.sort_by || 'created_at');
const sortDir = ref(props.filters?.direction || 'desc');

const filters = reactive({
    search: props.filters?.search || '',
    category_id: props.filters?.category_id || '',
});

function fetchProducts() {
    router.get(
        '/products',
        {
            search: filters.search,
            category_id: filters.category_id,
            sort_by: sortField.value,
            direction: sortDir.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}

function sortBy(field: any) {
    if (sortField.value === field) {
        sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDir.value = 'asc';
    }

    fetchProducts();
}

function goToPage(url: string) {
    const params = new URLSearchParams(url.split('?')[1]);
    router.get(
        '/products',
        {
            page: params.get('page'),
            search: filters.search,
            category_id: filters.category_id,
            sort_by: sortField.value,
            direction: sortDir.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}

function resetFilters() {
    filters.search = '';
    filters.category_id = '';
    sortField.value = 'created_at';
    sortDir.value = 'desc';
    fetchProducts();
}

function deleteProduct(id: any) {
    if (confirm('Удалить товар?')) {
        router.delete(`/products/${id}`, {
            onSuccess: () => fetchProducts(),
        });
    }
}
</script>
