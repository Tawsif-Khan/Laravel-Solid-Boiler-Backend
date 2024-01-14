<?php

namespace App\Enums\Resource;

use App\Traits\Enumerrayble;

enum Type: string
{
    use Enumerrayble;
    case Book = 'book';
    case Software = 'software';
    case ClassVideo = 'class-video';
    case Seminar = 'seminar';
}
