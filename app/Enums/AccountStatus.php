<?php

namespace App\Enums;

enum AccountStatus: string
{
    case Aktif = 'Aktif';
    case Nonaktif = 'Nonaktif';
    case Ditangguhkan = 'Ditangguhkan';
    case MenungguVerifikasi = 'Menunggu Verifikasi';
}
