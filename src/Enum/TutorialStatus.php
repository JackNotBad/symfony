<?php

namespace App\Enum;

enum TutorialStatus: string
{
    case DRAFT = 'Brouillon';
    case SCHEDULED = 'Programmé';
    case PUBLISHED = 'Publié';
    case ARCHIVED = 'Archivé';
}