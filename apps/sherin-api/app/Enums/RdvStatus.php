<?php

namespace App\Enums;

enum RdvStatus: int
{
    case Active = 0;
    case Cancelled = 1;
}
