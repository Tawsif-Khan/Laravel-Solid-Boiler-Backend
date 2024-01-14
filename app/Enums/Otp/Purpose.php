<?php

namespace App\Enums\Otp;

use App\Traits\Enumerrayble;

enum Purpose: string
{
    use Enumerrayble;

    case Authentication = 'authentication';
    case Verification = 'verification';
    case Recovery = 'recovery';

}
