<?php

namespace App\Entity;

use App\Controller\CarouselImageController;
use App\Repository\CarouselRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: CarouselRepository::class)]
class Carousel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[NotBlank]
    #[Length(max: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[NotBlank]
    #[Length(max: 50)]
    private ?string $page = null;

    #[ORM\Column(length: 50)]
    #[NotBlank]
    #[Length(max: 50)]
    private ?string $position = null;

    #[ORM\Column]
    private ?bool $fade = false;

    #[ORM\Column]
    private ?bool $autoplay = true;

    /**
     * Interval is in SECONDS
     *
     * @var int|null
     */
    #[ORM\Column(type: Types::SMALLINT)]
    #[Range(min: 1, max: 100)]
    private ?int $interval = 10;

    #[ORM\Column]
    private ?bool $indicators = false;

    #[ORM\Column]
    private ?bool $controls = true;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'carousel', targetEntity: CarouselImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $carouselImages;

    public function __construct()
    {
        $this->carouselImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function isFade(): ?bool
    {
        return $this->fade;
    }

    public function setFade(bool $fade): self
    {
        $this->fade = $fade;

        return $this;
    }

    public function isAutoplay(): ?bool
    {
        return $this->autoplay;
    }

    public function setAutoplay(bool $autoplay): self
    {
        $this->autoplay = $autoplay;

        return $this;
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

    public function isIndicators(): ?bool
    {
        return $this->indicators;
    }

    public function setIndicators(bool $indicators): self
    {
        $this->indicators = $indicators;

        return $this;
    }

    public function isControls(): ?bool
    {
        return $this->controls;
    }

    public function setControls(bool $controls): self
    {
        $this->controls = $controls;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, CarouselImage>
     */
    public function getCarouselImages(): Collection
    {
        return $this->carouselImages;
    }

    public function addCarouselImage(CarouselImage $carouselImage): self
    {
        if (!$this->carouselImages->contains($carouselImage)) {
            $this->carouselImages->add($carouselImage);
            $carouselImage->setCarousel($this);
        }

        return $this;
    }

    public function removeCarouselImage(CarouselImage $carouselImage): self
    {
        if ($this->carouselImages->removeElement($carouselImage)) {
            // set the owning side to null (unless already changed)
            if ($carouselImage->getCarousel() === $this) {
                $carouselImage->setCarousel(null);
            }
        }

        return $this;
    }
}
