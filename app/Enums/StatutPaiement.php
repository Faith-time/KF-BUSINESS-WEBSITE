<?php

namespace App\Enums;

enum StatutPaiement: string
{
    case PENDING = 'en_attente';
    case PROCESSING = 'traitement';
    case COMPLETED = 'complété';
    case FAILED = 'échoué';
    case REFUNDED = 'remboursé';
    case CANCELLED = 'annulé';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'En attente',
            self::PROCESSING => 'Traitement en cours',
            self::COMPLETED => 'Complété',
            self::FAILED => 'Échoué',
            self::REFUNDED => 'Remboursé',
            self::CANCELLED => 'Annulé',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::PROCESSING => 'blue',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
            self::REFUNDED => 'orange',
            self::CANCELLED => 'slate',
        };
    }
}
