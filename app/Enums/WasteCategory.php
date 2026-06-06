<?php

namespace App\Enums;

enum WasteCategory: string
{
    case Organik = 'Organik';
    case Anorganik = 'Anorganik';
    case Residu = 'Residu';
    case Khusus = 'Khusus';
    case B3RumahTangga = 'B3 Rumah Tangga';
}
