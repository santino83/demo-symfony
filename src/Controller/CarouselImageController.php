<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Entity\CarouselImage;
use App\Form\CarouselImageType;
use App\Repository\CarouselImageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/carousel/{id}/images', name: 'admin_carousel_images_')]
#[IsGranted('ROLE_USER')]
class CarouselImageController extends AbstractController
{

    public function __construct(private CarouselImageRepository $repository)
    {
    }

    #[Route('/', name: 'show', methods: ['GET', 'POST'])]
    public function show(Carousel $carousel, Request $request): Response
    {
        $carouselImage = new CarouselImage();
        $carouselImage->setCarousel($carousel)
            ->setPosition($carousel->getCarouselImages()->count() + 1);

        $basePath = $this->getParameter('upload_carousel_images_directory') . DIRECTORY_SEPARATOR . $carousel->getId();

        $form = $this->createForm(CarouselImageType::class, $carouselImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {

                $newFileName = 'image' . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $basePath,
                        $newFileName
                    );

                    $carouselImage->setImagePath($newFileName);
                    $this->repository->append($carouselImage);

                    return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);

                } catch (FileException|\Exception $e) {
                    $form->get('image')->addError(new FormError("Cannot Upload Image. Please try again"));
                }

            } else {
                $form->get('image')->addError(new FormError("An Image is required"));
            }

        }

        return $this->render('carousel_images/show.html.twig', [
            'carousel' => $carousel,
            'form' => $form
        ]);
    }

    #[Route('/delete/{imageId}', name: 'delete', methods: ['POST'])]
    #[Entity('carouselImage', expr: 'repository.find(imageId)')]
    public function delete(Carousel $carousel, CarouselImage $carouselImage, Request $request): Response
    {
        if ($carouselImage->getCarousel()->getId() !== $carousel->getId()) {
            return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$carouselImage->getId(), $request->request->get('_token'))) {
            $this->repository->deleteFromPosition($carouselImage);

            try{
                $basePath = $this->getParameter('upload_carousel_images_directory') . DIRECTORY_SEPARATOR . $carousel->getId();
                $fs = new Filesystem();
                $fs->remove($basePath.DIRECTORY_SEPARATOR.$carouselImage->getImagePath());

            }catch (\Exception $ex){
                // do nothing on deleting file errors
            }

        }

        return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/change/{imageId}/up', name: 'change_up', methods: ['POST'])]
    #[Entity('carouselImage', expr: 'repository.find(imageId)')]
    public function changePositionUp(Carousel $carousel, CarouselImage $carouselImage, Request $request): Response
    {
        if ($carouselImage->getCarousel()->getId() !== $carousel->getId()) {
            return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('changeUp'.$carouselImage->getId(), $request->request->get('_token'))) {
            $this->repository->moveUp($carouselImage);
        }

        return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/change/{imageId}/down', name: 'change_down', methods: ['POST'])]
    #[Entity('carouselImage', expr: 'repository.find(imageId)')]
    public function changePositionDown(Carousel $carousel, CarouselImage $carouselImage, Request $request): Response
    {
        if ($carouselImage->getCarousel()->getId() !== $carousel->getId()) {
            return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('changeDown'.$carouselImage->getId(), $request->request->get('_token'))) {
            $this->repository->moveDown($carouselImage);
        }

        return $this->redirectToRoute('admin_carousel_images_show', ['id' => $carousel->getId()], Response::HTTP_SEE_OTHER);
    }


}