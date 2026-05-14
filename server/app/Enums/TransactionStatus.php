<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case DRAFT = 'draft';           // Черновик
    case PENDING = 'pending';       // Ожидает оплаты
    case PAID = 'paid';             // Оплачено
    case PARTIALLY_PAID = 'partially_paid'; // Частично оплачено
    case CANCELLED = 'cancelled';   // Отменена
    case REFUNDED = 'refunded';     // Возврат
    case COMPLETED = 'completed';   // Завершена
}