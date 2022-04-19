<?php

namespace App\Enum;

enum PostStatus: string
{
    case Draft = 'draft';
    case Enable = 'enable';
    case Disable = 'disable';
}
