<?php

namespace App\Entity;


use App\Repository\WebAgencyRepository;
use Doctrine\ORM\Mapping as ORM;

use App\LinkwebBundle\WebAgencyBundle\Entity\AbstractWebAgency as SharedWebAgency;

#[ORM\Entity(repositoryClass: WebAgencyRepository::class)]
class WebAgency extends SharedWebAgency
{
   // Nothing to do here
}
