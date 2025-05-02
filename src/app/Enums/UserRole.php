<?php

namespace App\Enums;
enum UserRole: string
{
    case ADMIN = 'admin';
    case PARTNER = 'partner';
    case EMPLOYEE = 'employee';
}
