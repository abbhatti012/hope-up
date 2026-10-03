<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case ERROR = 'error';
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
}
