<?php

namespace App\Enum;

enum TodoPriority: string
{
    case NORMAL = 'normal';
    case LOW = 'low';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::NORMAL => 'Normal',
            self::LOW => 'Low',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }
}
