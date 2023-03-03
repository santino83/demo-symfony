<?php

namespace App\Twig\Components;

use App\Entity\Carousel;
use App\Repository\CarouselRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('carousel')]
final class CarouselComponent
{

    public string $page;

    public string $position;

    public string $elementId;

    public function __construct(private CarouselRepository $repository){

    }

    public function getCarousel(): ?Carousel
    {
        return $this->repository->findOneBy(['page' => $this->page, 'position' => $this->position]);
    }

}
