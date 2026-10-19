<?php

namespace App\Enum;

enum Unit: string
{
    case Grams = 'g';
    case Tablespoons = 'tbsp';
    case Pieces = 'pcs';

    public function label(): string
    {
        return match ($this) {
            self::Grams => 'grams',
            self::Tablespoons => 'tablespoons',
            self::Pieces => 'pieces',
        };
    }
}
