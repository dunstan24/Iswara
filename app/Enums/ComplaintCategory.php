<?php

namespace App\Enums;

enum ComplaintCategory: string
{
    case Pengangkutan = 'Pengangkutan';
    case Lingkungan = 'Lingkungan';
    case Pelayanan = 'Pelayanan';
    case Pemilahan = 'Pemilahan';
    case Fasilitas = 'Fasilitas';
    case Keuangan = 'Keuangan';
}
