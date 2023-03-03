<?php

namespace App\Entity;

use App\Repository\ThematicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ThematicRepository::class)]
class Thematic
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

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'thematic', targetEntity: ThematicImage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $thematicImages;

    public function __construct()
    {
        $this->thematicImages = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ThematicImage>
     */
    public function getThematicImages(): Collection
    {
        return $this->thematicImages;
    }

    public function addThematicImage(ThematicImage $thematicImage): self
    {
        if (!$this->thematicImages->contains($thematicImage)) {
            $this->thematicImages->add($thematicImage);
            $thematicImage->setThematic($this);
        }

        return $this;
    }

    public function removeThematicImage(ThematicImage $thematicImage): self
    {
        if ($this->thematicImages->removeElement($thematicImage)) {
            // set the owning side to null (unless already changed)
            if ($thematicImage->getThematic() === $this) {
                $thematicImage->setThematic(null);
            }
        }

        return $this;
    }
}
