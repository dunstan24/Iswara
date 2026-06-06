<?php

namespace App\Enums;

enum RoadCondition: string
{
    case Baik = 'Baik';
    case Sedang = 'Sedang';
    case RusakRingan = 'Rusak Ringan';
    case RusakBerat = 'Rusak Berat';
}
