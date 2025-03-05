<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\ProjectMockupRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProjectMockupRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    uriTemplate: '/projects/{projectId}/mockups',
    operations: [new GetCollection()],
    uriVariables: [
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs')
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
        'projectId' => new Link(toProperty: 'project', fromClass: Project::class, description: 'The project to which the mockup belongs')
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
    /**
     * @var UuidInterface
     */
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface $id;

    /**
     * @var Project
     */
    #[ORM\ManyToOne(targetEntity: Project::class, cascade: ['persist'], inversedBy: 'projectMockups')]
    #[Groups(['project:read'])]
    private Project $project;

    /**
     * @var string
     */
    #[ORM\Column(name: 'domain_name', type: 'string')]
    private string $domainName;

    /**
     * @var string
     */
    #[ORM\Column(name: 'theme', type: 'string')]
    private string $theme;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'is_mockup', type: 'boolean', options: ['default' => true])]
    private bool $isMockup = true;

    /**
     * @var string
     */
    #[ORM\Column(name: 'login', type: 'string')]
    private string $login;

    /**
     * @var string
     */
    #[ORM\Column(name: 'password', type: 'string')]
    private string $password;

    /**
     * @var array
     */
    #[ORM\Column(name: 'data', type: 'json')]
    private array $data;

    /**
     * @var string
     */
    #[ORM\Column(name: 'image_zip_path', type: 'string')]
    private string $imageZipPath;

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
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     *
     * @return self
     */
    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomainName(): string
    {
        return $this->domainName;
    }

    /**
     * @param string $domainName
     *
     * @return self
     */
    public function setDomainName(string $domainName): self
    {
        $this->domainName = $domainName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     *
     * @return self
     */
    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsMockup(): bool
    {
        return $this->isMockup;
    }

    /**
     * @param bool $isMockup
     *
     * @return self
     */
    public function setIsMockup(bool $isMockup): self
    {
        $this->isMockup = $isMockup;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return self
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    /**
     * @return string
     */
    public function getImageZipPath(): string
    {
        return $this->imageZipPath;
    }

    /**
     * @param string $imageZipPath
     *
     * @return self
     */
    public function setImageZipPath(string $imageZipPath): self
    {
        $this->imageZipPath = $imageZipPath;

        return $this;
    }
}
