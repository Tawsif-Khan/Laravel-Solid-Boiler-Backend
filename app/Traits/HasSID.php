<?php

namespace App\Traits;

trait HasSID
{
    private const SUPER_ADMIN_POSTFIX = 'SA';

    private const ADMIN_POSTFIX = 'A';

    private const EXECUTIVE_POSTFIX = 'E';

    private const TEACHER_POSTFIX = 'T';

    private const STUDENT_POSTFIX = 'S';

    private const SEPARATOR = '-';

    private function sid(string $postfix, int $id)
    {
        return 'CC'.now()->year.self::SEPARATOR.now()->year.str_pad($id, 5, '0', STR_PAD_LEFT).self::SEPARATOR.$postfix;
    }
}
