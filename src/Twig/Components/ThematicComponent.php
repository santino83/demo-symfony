<?php

namespace App\Twig\Components;

use App\Entity\Thematic;
use App\Repository\ThematicRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('thematic')]
final class ThematicComponent
{

    public string $page;

    public string $position;

    public string $colSize;

    public function __construct(private ThematicRepository $repository)
    {

    }

    public function getThematic(): ?Thematic
    {
        return $this->repository->findOneBy(['page' => $this->page, 'position' => $this->position]);
    }

}
