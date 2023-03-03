<?php

namespace App\DataFixtures;

use App\Entity\Carousel;
use App\Entity\CarouselImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class LoadCarouselFixtures extends Fixture
{

    private string $sourceFolder;
    private string $destFolder;

    public function __construct(ContainerInterface $container)
    {
        $this->sourceFolder = $container->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'demo_data'.DIRECTORY_SEPARATOR.'carousels';
        $this->destFolder = $container->getParameter('upload_carousel_images_directory');
    }

    public function load(ObjectManager $manager): void
    {

        $carousel = new Carousel();
        $carousel->setPosition('main')
            ->setAutoplay(true)
            ->setFade(true)
            ->setControls(true)
            ->setName('Main')
            ->setIndicators(false)
            ->setInterval(5)
            ->setPage('homepage');

        $manager->persist($carousel);
        $manager->flush();

        $fs = new Filesystem();
        $fs->mkdir($this->destFolder.DIRECTORY_SEPARATOR.$carousel->getId());

        foreach ([1,2,3,4] as $item) {

            $image = new CarouselImage();
            $image->setCarousel($carousel)
                ->setInterval(0)
                ->setPosition($item)
                ->setImagePath('image-s'.$item.'.jpg');

            $manager->persist($image);

            $fs->copy($this->sourceFolder.DIRECTORY_SEPARATOR.'s'.$item.'.jpg',
                $this->destFolder.DIRECTORY_SEPARATOR.$carousel->getId().DIRECTORY_SEPARATOR.'image-s'.$item.'.jpg');
        }

        $manager->flush();


    }
}
