<?php

namespace App\Enums;

enum PostStatus: string
{
    case Draft = 'draft';
    case Enable = 'enable';
    case Disable = 'disable';
}
