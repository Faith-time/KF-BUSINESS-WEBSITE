<?php

namespace App\Enums;

enum StatutProjet: string
{
    case PREPARATION = 'préparation';
    case FINANCEMENT = 'financement';
    case EN_COURS = 'en_cours';
    case COMPLETED = 'terminé';
    case SUSPENDED = 'suspendu';
    case CANCELLED = 'annulé';

    public function label(): string
    {
        return match($this) {
            self::PREPARATION => 'Préparation',
            self::FINANCEMENT => 'Financement',
            self::EN_COURS => 'En cours',
            self::COMPLETED => 'Terminé',
            self::SUSPENDED => 'Suspendu',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PREPARATION => 'gray',
            self::FINANCEMENT => 'blue',
            self::EN_COURS => 'green',
            self::COMPLETED => 'emerald',
            self::SUSPENDED => 'yellow',
            self::CANCELLED => 'red',
        };
    }
}
