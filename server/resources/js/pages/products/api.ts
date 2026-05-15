import axios from "axios"

// 1. Получить все продукты
axios.get('/api/products')

// 2. Получить активные продукты (с пагинацией)
axios.get('/api/products/active?per_page=10')

// 3. Получить продукты по категории
axios.get('/api/products/category/123?per_page=15')

// 4. Получить один продукт
axios.get('/api/products/123')

// 5. Создать продукт
axios.post('/api/products', {
    product_name: 'Доска обрезная',
    sku: 'DO-100x25-6m',
    base_price: 15000,
    retail_price: 25000,
    category_id: 'cat_123',
    unit_id: 'unit_456'
})

// 6. Массовое создание
axios.post('/api/products/bulk', {
    products: [
        { product_name: 'Брус 100x100', sku: 'BR-100x100' },
        { product_name: 'Брус 150x150', sku: 'BR-150x150' }
    ]
})

// ИЛИ просто массив
axios.post('/api/products/bulk', [
    { product_name: 'Брус 100x100', sku: 'BR-100x100' },
    { product_name: 'Брус 150x150', sku: 'BR-150x150' }
])

// 7. Обновить продукт
axios.put('/api/products/123', {
    product_name: 'Доска обрезная новая',
    retail_price: 27000
})

// 8. Удалить один продукт
axios.delete('/api/products/123')

// 9. Массовое удаление (разные варианты)

// Вариант 1: массив ids в теле
axios.delete('/api/products/bulk', { 
    data: { ids: ['id1', 'id2', 'id3'] }
})

// Вариант 2: JSON массив
axios.delete('/api/products/bulk', {
    data: ['id1', 'id2', 'id3']
})

// Вариант 3: строка через запятую
axios.delete('/api/products/bulk', {
    data: { ids_string: 'id1,id2,id3' }
})