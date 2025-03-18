<?php

namespace App\Entity;


use App\Repository\WebAgencyRepository;
use Doctrine\ORM\Mapping as ORM;

use App\LinkwebBundle\WebAgencyBundle\src\Entity\AbstractWebAgency;

#[ORM\Entity(repositoryClass: WebAgencyRepository::class)]
class WebAgency extends AbstractWebAgency
{
   // Nothing to do here
}
