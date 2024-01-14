<?php

namespace App\Enums\User;

use App\Traits\Enumerrayble;

enum Role: string
{
    use Enumerrayble;
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Executive = 'executive';
    case Teacher = 'teacher';
    case Student = 'student';
}
