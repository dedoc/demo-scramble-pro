<?php

namespace App\Enums;

enum CompanySize: string
{
    case SINGLE = '1';
    case SMALL = '2-10';
    case MEDIUM = '11-50';
    case LARGE = '51+';
}
