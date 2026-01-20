<?php

namespace App\Enum;

enum Priority: string
{
    case STAT  = 'STAT ';
    case URGENT = 'URGENT ';
    case ROUTINE = 'ROUTINE';
}
