<?php

namespace App\Enums;

enum TypeMedia: string
{
    case IMAGE = 'image';
    case VIDEO = 'vidéo';
    case DOCUMENT = 'document';
    case AUDIO = 'audio';

    public function label(): string
    {
        return match($this) {
            self::IMAGE => 'Image',
            self::VIDEO => 'Vidéo',
            self::DOCUMENT => 'Document',
            self::AUDIO => 'Audio',
        };
    }

    public function mimeTypes(): array
    {
        return match($this) {
            self::IMAGE => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            self::VIDEO => ['video/mp4', 'video/webm', 'video/quicktime'],
            self::DOCUMENT => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            self::AUDIO => ['audio/mpeg', 'audio/wav', 'audio/ogg'],
        };
    }
}
