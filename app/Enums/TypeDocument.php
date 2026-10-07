<?php

namespace App\Enums;

enum TypeDocument: string
{
    case PRESENTATION = 'présentation';
    case PROSPECTUS = 'prospectus';
    case CONTRAT = 'contrat';
    case RAPPORT_FINANCIER = 'rapport_financier';
    case ATTESTATION = 'attestation';
    case CERTIFICAT_PARTICIPATION = 'certificat_participation';
    case AVIS_PAIEMENT = 'avis_paiement';
    case AUTRE = 'autre';

    public function label(): string
    {
        return match($this) {
            self::PRESENTATION => 'Présentation',
            self::PROSPECTUS => 'Prospectus',
            self::CONTRAT => 'Contrat',
            self::RAPPORT_FINANCIER => 'Rapport financier',
            self::ATTESTATION => 'Attestation',
            self::CERTIFICAT_PARTICIPATION => 'Certificat de participation',
            self::AVIS_PAIEMENT => 'Avis de paiement',
            self::AUTRE => 'Autre',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PRESENTATION => 'FileText',
            self::PROSPECTUS => 'BookOpen',
            self::CONTRAT => 'FileCheck',
            self::RAPPORT_FINANCIER => 'BarChart3',
            self::ATTESTATION => 'Award',
            self::CERTIFICAT_PARTICIPATION => 'Shield',
            self::AVIS_PAIEMENT => 'DollarSign',
            self::AUTRE => 'File',
        };
    }
}
