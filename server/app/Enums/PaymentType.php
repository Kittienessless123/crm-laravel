<?php

namespace App\Enums;

enum PaymentType: string
{
    case CASH = 'CASH';
    case CARD = 'CARD';
    case TRANSFER = 'TRANSFER';
    case SBP = 'SBP';              // Система быстрых платежей
    case CRYPTO = 'CRYPTO';        // Криптовалюта
}