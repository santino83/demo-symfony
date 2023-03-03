<?php

namespace App\Entity;

use App\Repository\ThematicImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ThematicImageRepository::class)]
class ThematicImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Length(max: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Length(max: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Length(max: 150)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = 1;

    #[ORM\ManyToOne(inversedBy: 'thematicImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thematic $thematic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getThematic(): ?Thematic
    {
        return $this->thematic;
    }

    public function setThematic(?Thematic $thematic): self
    {
        $this->thematic = $thematic;

        return $this;
    }
}
