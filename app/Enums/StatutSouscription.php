<?php

namespace App\Enums;

enum StatutSouscription: string
{
    case PENDING = 'en_attente';
    case APPROVED = 'approuvée';
    case REJECTED = 'rejetée';
    case CANCELLED = 'annulée';
    case COMPLETED = 'terminée';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::APPROVED => 'Approuvée',
            self::REJECTED => 'Rejetée',
            self::CANCELLED => 'Annulée',
            self::COMPLETED => 'Terminée',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'amber',
            self::APPROVED => 'green',
            self::REJECTED => 'red',
            self::CANCELLED => 'gray',
            self::COMPLETED => 'emerald',
        };
    }
}
