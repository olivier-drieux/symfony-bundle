<?php

namespace App\Entity;

use App\LinkwebBundle\WebAgencyBundle\src\Entity\AbstractWebAgency;
use App\Repository\WebAgencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebAgencyRepository::class)]
class WebAgency extends AbstractWebAgency
{
    // Nothing to do here
}
