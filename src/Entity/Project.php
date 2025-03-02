<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(

)]
class Project
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    /**
     * @var Collection List of ProjectMockups associated with the Project
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectMockup::class, cascade: ['persist', 'remove'])]
    #[ApiFilter(SearchFilter::class, properties: ['projectMockups.domainName' => 'ipartial'])]
    private Collection $projectMockups;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string')]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private string $name;

    /**
     * @var 'completed'|'mockup'|'partial'
     */
    private string $status;

    /**
     * @var array
     */
    #[ORM\Column(name: 'data', type: 'json')]
    private array $data = [];

    /**
     * @var \DateTimeImmutable
     */
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: true)]
    private \DateTimeImmutable|null $updatedAt = null;

    public function __construct()
    {
        $this->projectMockups = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
    
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return ProjectMockup[]
     */
    public function getProjectMockups(): array
    {
        return $this->projectMockups->getValues();
    }

    /**
     * @param ProjectMockup $projectMockup
     *
     * @return self
     */
    public function addProjectMockup(ProjectMockup $projectMockup): self
    {
        if (false === $this->projectMockups->contains($projectMockup)) {
            $projectMockup->setProject($this);
            $this->projectMockups->add($projectMockup);
        }

        return $this;
    }

    /**
     * @param ProjectMockup $projectMockup
     *
     * @return self
     */
    public function removeProjectMockup(ProjectMockup $projectMockup): self
    {
        if ($this->projectMockups->contains($projectMockup)) {
            $this->projectMockups->removeElement($projectMockup);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
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
     * @return 'completed'|'mockup'|'partial'
     */
    public function getStatus(): string
    {
        if (0 === \count($this->projectMockups)) {
            return 'partial';
        }

        foreach ($this->projectMockups as $projectMockup) {
            if (false === $projectMockup->getIsMockup()) {
                return 'completed';
            }
        }

        return 'mockup';
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
