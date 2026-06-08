<?php

namespace App\Enums;

enum PriorityLevel: string
{
    case Rendah = 'Rendah';
    case Sedang = 'Sedang';
    case Tinggi = 'Tinggi';
    case Darurat = 'Darurat';
}
