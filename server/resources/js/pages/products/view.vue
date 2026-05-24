<template>
    <div style="padding: 20px; max-width: 800px; margin: 0 auto">
        <Link
            href="/products"
            style="color: blue; margin-bottom: 20px; display: inline-block"
        >
            ← Назад к списку
        </Link>

        <h1>{{ product!.product_name }}</h1>

        <div
            style="
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
                margin-top: 20px;
            "
        >
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>SKU:</strong> {{ product!.sku || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Внешний ID:</strong> {{ product!.product_id || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Категория:</strong> {{ product!.category?.name || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Ед. измерения:</strong> {{ product!.unit?.name || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Базовая цена:</strong> {{ product!.base_price }}
                {{ product!.currency }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Розничная цена:</strong> {{ product!.retail_price }}
                {{ product!.currency }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Оптовая цена:</strong>
                {{ product!.wholesale_price || '—' }} {{ product!.currency }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Наценка:</strong> {{ product!.margin }}%
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Продавец:</strong> {{ product!.seller?.name || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Поставщик:</strong> {{ product!.supplier?.name || '—' }}
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>Статус:</strong>
                <span :style="{ color: product!.is_active ? 'green' : 'red' }">
                    {{ product!.is_active ? 'Активен' : 'Неактивен' }}
                </span>
            </div>
            <div style="padding: 10px; background: #f9f9f9; border-radius: 5px">
                <strong>В наличии:</strong>
                <span
                    :style="{ color: product!.is_available ? 'green' : 'red' }"
                >
                    {{ product!.is_available ? 'Да' : 'Нет' }}
                </span>
            </div>
        </div>

        <div
            v-if="product!.description"
            style="
                margin-top: 20px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 5px;
            "
        >
            <strong>Описание:</strong>
            <p>{{ product!.description }}</p>
        </div>

        <div
            v-if="product!.size"
            style="
                margin-top: 10px;
                padding: 15px;
                background: #f9f9f9;
                border-radius: 5px;
            "
        >
            <strong>Размер:</strong> {{ product!.size }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    product: Object,
});
</script>
