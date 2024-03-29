<?php
declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class SearchHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $imageName;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $tagName = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $searchedAt;

    #[ORM\Column(type: 'boolean')]
    private bool $searchAllTags;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(?string $tagName): self
    {
        $this->tagName = $tagName;
        return $this;
    }

    public function getSearchedAt(): \DateTimeInterface
    {
        return $this->searchedAt;
    }

    public function setSearchedAt(\DateTimeInterface $searchedAt): self
    {
        $this->searchedAt = $searchedAt;
        return $this;
    }

    public function isSearchAllTags(): bool
    {
        return $this->searchAllTags;
    }

    public function setSearchAllTags(bool $searchAllTags): void
    {
        $this->searchAllTags = $searchAllTags;
    }
}
