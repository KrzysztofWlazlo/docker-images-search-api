<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DockerImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ORM\Entity(repositoryClass: DockerImageRepository::class)]
class DockerImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $name;

    public function getId(): ?int
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
}

