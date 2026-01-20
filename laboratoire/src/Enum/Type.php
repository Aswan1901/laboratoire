<?php

namespace App\Enum;

enum Type: string
{
    case BLOOD = 'BLOOD';
    case TISSUE = 'TISSUE';
    case URINE = 'URINE';

    case GENERAL = 'GENERAL';
}
