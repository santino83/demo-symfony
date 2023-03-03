<?php

namespace App\DataFixtures;

use App\Entity\Carousel;
use App\Entity\CarouselImage;
use App\Entity\Thematic;
use App\Entity\ThematicImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class LoadThematicFixtures extends Fixture
{

    private string $sourceFolder;
    private string $destFolder;

    public function __construct(ContainerInterface $container)
    {
        $this->sourceFolder = $container->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'demo_data'.DIRECTORY_SEPARATOR.'thematics';
        $this->destFolder = $container->getParameter('upload_thematic_images_directory');
    }

    public function load(ObjectManager $manager): void
    {

        $thematic = new Thematic();
        $thematic->setPage('homepage')
            ->setName('Main')
            ->setPosition('main');

        $manager->persist($thematic);
        $manager->flush();

        $fs = new Filesystem();
        $fs->mkdir($this->destFolder.DIRECTORY_SEPARATOR.$thematic->getId());

        $data = [
            1 => ['title' => 'Mare Italia', 'description' => 'Le migliori spiagge italiane Puglia, Sardegna e molto altro'],
            2 => ['title' => 'Adventure', 'description' => 'Una selezione dei nostri pacchetti per chi vuole vivere avventure forti'],
            3 => ['title' => 'Neve', 'description' => 'Per chi ha voglia di sciare e per chi vuole rilassarsi'],
            4 => ['title' => 'Glamping', 'description' => 'Il campeggio di lusso una vacanza diversa ma piena di comfort'],
        ];

        foreach ($data as $item => $value) {

            $image = new ThematicImage();
            $image->setThematic($thematic)
                ->setPosition($item)
                ->setImagePath('image-t'.$item.'.png')
                ->setTitle($value['title'])
                ->setDescription($value['description']);

            $manager->persist($image);

            $fs->copy($this->sourceFolder.DIRECTORY_SEPARATOR.'t'.$item.'.png',
                $this->destFolder.DIRECTORY_SEPARATOR.$thematic->getId().DIRECTORY_SEPARATOR.'image-t'.$item.'.png');
        }

        $manager->flush();


    }
}
