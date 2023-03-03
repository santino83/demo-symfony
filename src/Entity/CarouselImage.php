<?php

namespace App\Entity;

use App\Repository\CarouselImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[ORM\Entity(repositoryClass: CarouselImageRepository::class)]
class CarouselImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Interval is in SECONDS
     *
     * @var int|null
     */
    #[ORM\Column(type: Types::SMALLINT)]
    #[Length(min: 0, max: 100)]
    private ?int $interval = 0;

    #[ORM\Column(length: 255)]
    #[Length(max: 255)]
    private ?string $imagePath = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Length(max: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Length(max: 150)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'carouselImages')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull]
    private ?Carousel $carousel = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInterval(): ?int
    {
        return $this->interval;
    }

    public function setInterval(int $interval): self
    {
        $this->interval = $interval;

        return $this;
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

    public function getCarousel(): ?Carousel
    {
        return $this->carousel;
    }

    public function setCarousel(?Carousel $carousel): self
    {
        $this->carousel = $carousel;

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
}
