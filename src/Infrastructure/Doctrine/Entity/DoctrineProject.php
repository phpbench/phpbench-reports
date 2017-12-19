<?php

namespace App\Infrastructure\Doctrine\Entity;

use App\Domain\Project\Project;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\User\BenchUser;
use App\Domain\Project\ProjectName;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="project", 
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="search_idx", columns={"namespace", "name"})
 *     }
 * )
 */
class DoctrineProject implements Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Infrastructure\Doctrine\Entity\DoctrineUser")
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $namespace;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $apiKey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    public function __construct(DoctrineUser $user, ProjectName $projectName, string $apiKey)
    {
        $this->user = $user;
        $this->namespace = $projectName->namespace();
        $this->name = $projectName->name();
        $this->apiKey = $apiKey;
    }

    public function name(): ProjectName
    {
        return ProjectName::fromNamespaceAndName($this->namespace, $this->name);
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function user(): BenchUser
    {
        return $this->user;
    }

    public function id(): string
    {
        return $this->id;
    }
}
