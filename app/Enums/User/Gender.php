<?php

namespace App\Enums\User;

use App\Traits\Enumerrayble;

enum Gender: string
{
    use Enumerrayble;
    case Male = 'male';
    case Female = 'female';
}
