<?php

namespace App\Enums;

enum PlaceType: string
{
    case TempatIbadah = 'Tempat Ibadah';
    case Banjar = 'Banjar';
    case Sekolah = 'Sekolah';
    case Pasar = 'Pasar';
    case TPS3R = 'TPS3R';
    case Setra = 'Setra';
    case KantorPemerintah = 'Kantor Pemerintah';
    case Lapangan = 'Lapangan';
    case BankSampah = 'Bank Sampah';
    case Lainnya = 'Lainnya';
}
