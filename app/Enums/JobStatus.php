<?php

namespace App\Enums;

enum JobStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}
