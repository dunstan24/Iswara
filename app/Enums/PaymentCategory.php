<?php

namespace App\Enums;

enum PaymentCategory: string
{
    case Tunai = 'Tunai';
    case Transfer = 'Transfer';
    case Digital = 'Digital';
    case Kolektor = 'Kolektor';
}
