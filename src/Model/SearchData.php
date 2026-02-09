<?php

namespace App\Model;

use App\Entity\Campus;

class SearchData
{
    public ?string $q = '';
    public ?Campus $campus = null;
}
