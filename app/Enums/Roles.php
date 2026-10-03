<?php

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';
    case USER = 'user';
    case SPECIALIST = 'specialist';
}
