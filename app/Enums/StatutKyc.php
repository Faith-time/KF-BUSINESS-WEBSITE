<?php

namespace App\Enums;

enum StatutKyc: string
{
    case NOT_STARTED = 'non_commencé';
    case SUBMITTED = 'soumis';
    case UNDER_REVIEW = 'en_révision';
    case APPROVED = 'approuvé';
    case REJECTED = 'rejeté';
    case NEEDS_REVISION = 'nécessite_révision';

    public function label(): string
    {
        return match($this) {
            self::NOT_STARTED => 'Non commencé',
            self::SUBMITTED => 'Soumis',
            self::UNDER_REVIEW => 'En révision',
            self::APPROVED => 'Approuvé',
            self::REJECTED => 'Rejeté',
            self::NEEDS_REVISION => 'Nécessite révision',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::NOT_STARTED => 'gray',
            self::SUBMITTED => 'blue',
            self::UNDER_REVIEW => 'yellow',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
            self::NEEDS_REVISION => 'orange',
        };
    }
}
