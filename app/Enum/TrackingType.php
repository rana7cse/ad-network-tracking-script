<?php

namespace App\Enum;

enum TrackingType: int
{
    case IMPRESSION = 1;
    case CLICK = 2;

    public static function getEnumValueByConv($conv) : ?int
    {
        return match ($conv) {
            'imp' => self::IMPRESSION->value,
            'click' => self::CLICK->value,
            default => null
        };
    }
}
