<?php

namespace App\Enums;

enum ProcessingMethod: string
{
    case Kompos = 'Kompos';
    case DaurUlang = 'Daur Ulang';
    case BankSampah = 'Bank Sampah';
    case ResiduKeTPA = 'Residu ke TPA';
    case PengelolaanKhusus = 'Pengelolaan Khusus';
}
