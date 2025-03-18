<?php

namespace App\Entity;

use App\LinkwebBundle\WebAgencyBundle\src\Entity\AbstractWebAgency;
use App\Repository\WebAgencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\AttributeOverride;
use Doctrine\ORM\Mapping\AttributeOverrides;

#[ORM\Entity(repositoryClass: WebAgencyRepository::class)]
class WebAgency extends AbstractWebAgency
{
    // Nothing to do here
}
