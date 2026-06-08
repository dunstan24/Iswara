<?php

namespace App\Enums;

enum ResponsibleRole: string
{
    case Operator = 'Operator';
    case Kesling = 'Kesling';
    case Manajemen = 'Manajemen';
    case Admin = 'Admin';
}
