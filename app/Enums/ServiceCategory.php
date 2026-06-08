<?php

namespace App\Enums;

enum ServiceCategory: string
{
    case RumahTangga = 'Rumah Tangga';
    case UsahaMikro = 'Usaha Mikro';
    case UsahaKecil = 'Usaha Kecil';
    case UsahaMenengah = 'Usaha Menengah';
    case Institusi = 'Institusi';
    case Pariwisata = 'Pariwisata';
}
