<?php

namespace App\Model;

use App\Entity\Campus;

class SearchData
{
    public string $q = '';
    public ?Campus $campus = null;
    public ?\DateTimeInterface $datemin = null;
    public ?\DateTimeInterface $datemax = null;
    public bool $isOrganisateur = false;
    public bool $isInscrit = false;
    public bool $isNotInscrit = false;
    public bool $isPassee = false;
}
