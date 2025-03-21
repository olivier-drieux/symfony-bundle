<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Linkweb\SharedBundle\Constant\SharedTables;

#[ORM\Entity]
#[ORM\Table(name: SharedTables::WEB_AGENCY)]
class WebAgency extends \Linkweb\SharedBundle\Entity\WebAgency
{
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $extraField = null;

    public function getExtraField(): ?string
    {
        return $this->extraField;
    }

    public function setExtraField(?string $extraField): self
    {
        $this->extraField = $extraField;
        return $this;
    }
}
