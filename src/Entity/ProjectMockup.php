<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    uriTemplate: '/projects/{projectId}/mockups',
    operations: [new GetCollection()],
    uriVariables: [
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs'),
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{projectId}/mockups/{id}',
    operations: [new Get()],
    uriVariables: [
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs'),
        'id' => new Link(fromClass: ProjectMockup::class),
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{projectId}/mockups',
    operations: [new Post()],
    uriVariables: [
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs'),
    ]
)]
#[ApiResource(
    uriTemplate: '/projects/{projectId}/mockups/{id}',
    operations: [new Delete()],
    uriVariables: [
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs'),
        'id' => new Link(fromClass: ProjectMockup::class),
    ]
)]
class ProjectMockup
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'projectMockups')]
    #[Groups(['project:read'])]
    private Project $project;

    #[ORM\Column(name: 'domain_name', type: 'string')]
    private string $domainName;

    #[ORM\Column(name: 'theme', type: 'string')]
    private string $theme;

    #[ORM\Column(name: 'is_mockup', type: 'boolean', options: ['default' => true])]
    private bool $isMockup = true;

    #[ORM\Column(name: 'login', type: 'string')]
    private string $login;

    #[ORM\Column(name: 'password', type: 'string')]
    private string $password;

    #[ORM\Column(name: 'data', type: 'json')]
    private array $data;

    #[ORM\Column(name: 'image_zip_path', type: 'string')]
    private string $imageZipPath;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getDomainName(): string
    {
        return $this->domainName;
    }

    public function setDomainName(string $domainName): self
    {
        $this->domainName = $domainName;

        return $this;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getIsMockup(): bool
    {
        return $this->isMockup;
    }

    public function setIsMockup(bool $isMockup): self
    {
        $this->isMockup = $isMockup;

        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getImageZipPath(): string
    {
        return $this->imageZipPath;
    }

    public function setImageZipPath(string $imageZipPath): self
    {
        $this->imageZipPath = $imageZipPath;

        return $this;
    }
}
