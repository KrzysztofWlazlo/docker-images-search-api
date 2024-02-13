<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DockerTagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ApiResource]
#[ORM\Entity(repositoryClass: DockerTagRepository::class)]
class DockerTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $tagName;

    #[ORM\Column(type: 'string', length: 50)]
    private string $status;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $lastModified;

    #[ORM\Column(type: 'string', length: 50)]
    private string $architecture;

    #[ORM\Column(type: 'string', length: 50)]
    private string $os;

    #[ORM\Column(type: 'float')]
    private float $size;

    #[ORM\ManyToOne(targetEntity: DockerImage::class)]
    #[ORM\JoinColumn(nullable: false)]
    private DockerImage $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getLastModified(): \DateTimeInterface
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;
        return $this;
    }

    public function getArchitecture(): string
    {
        return $this->architecture;
    }

    public function setArchitecture(string $architecture): self
    {
        $this->architecture = $architecture;
        return $this;
    }

    public function getOs(): string
    {
        return $this->os;
    }

    public function setOs(string $os): self
    {
        $this->os = $os;
        return $this;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getImage(): DockerImage
    {
        return $this->image;
    }

    public function setImage(DockerImage $image): self
    {
        $this->image = $image;
        return $this;
    }
}