<?php

namespace App\Enums\User;

use App\Traits\Enumerrayble;

enum Blood: string
{
    use Enumerrayble;

    case APositive = 'A+';
    case ANegative = 'A-';
    case BPositive = 'B+';
    case BNegative = 'B-';
    case ABPositive = 'AB+';
    case ABNegative = 'AB-';
    case OPositive = 'O+';
    case ONegative = 'O-';
}
