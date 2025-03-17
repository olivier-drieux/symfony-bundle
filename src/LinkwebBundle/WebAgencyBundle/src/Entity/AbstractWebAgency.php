<?php

namespace App\LinkwebBundle\WebAgencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use LinkwebBundle\Constant\SharedTables;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: SharedTables::WEB_AGENCY)]
#[UniqueEntity('name')]
class AbstractWebAgency
{
    /**
     * @var UuidInterface
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string|null $name = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string|null $website = null;

    /**
     * @return int|null
     */
    public function getId(): int|null
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): string|null
    {
        return $this->website;
    }

    /**
     * @param string $website
     *
     * @return self
     */
    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }
}
