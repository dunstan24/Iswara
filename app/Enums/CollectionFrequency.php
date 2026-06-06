<?php

namespace App\Enums;

enum CollectionFrequency: string
{
    case Harian = 'Harian';
    case DuaHariSekali = '2 Hari Sekali';
    case Mingguan = 'Mingguan';
    case SesuaiPermintaan = 'Sesuai Permintaan';
}
