<?php

namespace App\LinkwebBundle\WebAgencyBundle\src\Entity;

use App\LinkwebBundle\Constant\SharedTables;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[MappedSuperclass]
#[ORM\Table(name: SharedTables::WEB_AGENCY)]
#[UniqueEntity('name')]
class AbstractWebAgency
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups('output')]
    protected UuidInterface $id;

    #[ORM\Column(name: 'name', type: Types::STRING, unique: true)]
    #[Groups('output')]
    protected string $name;

    #[ORM\Column(name: 'html_names', type: Types::JSON)]
    #[Groups('output')]
    protected array $htmlNames = [];

    #[ORM\Column(name: 'url_keywords', type: Types::JSON)]
    #[Groups('output')]
    protected array $urlKeywords = [];

    #[ORM\Column(name: 'parked_page_keywords', type: Types::JSON)]
    #[Groups('output')]
    protected array $parkedPageKeywords = [];

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHtmlNames(): array
    {
        return $this->htmlNames;
    }

    public function setHtmlNames(array $htmlNames): self
    {
        $this->htmlNames = $htmlNames;

        return $this;
    }

    public function getUrlKeywords(): array
    {
        return $this->urlKeywords;
    }

    public function setUrlKeywords(array $urlKeywords): self
    {
        $this->urlKeywords = $urlKeywords;

        return $this;
    }

    public function getParkedPageKeywords(): array
    {
        return $this->parkedPageKeywords;
    }

    public function setParkedPageKeywords(array $parkedPageKeywords): self
    {
        $this->parkedPageKeywords = $parkedPageKeywords;

        return $this;
    }
}
