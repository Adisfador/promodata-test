<?php

namespace App\Enums;

enum ProcessStatus: int
{
    case Running  = 1;
    case Finished = 2;
    case Error    = 3;
}
