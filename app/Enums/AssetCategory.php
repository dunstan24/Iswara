<?php

namespace App\Enums;

enum AssetCategory: string
{
    case Bergerak = 'Bergerak';
    case TidakBergerak = 'Tidak Bergerak';
    case Digital = 'Digital';
}
