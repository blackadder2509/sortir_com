<?php

namespace App\Model;

use App\Entity\Campus;

class SearchData
{
    /**
     * @var string|null
     */
    public $q = '';

    /**
     * @var Campus|null
     */
    public $campus;
}
