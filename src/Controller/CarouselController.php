<?php

namespace App\Controller;

use App\Entity\Carousel;
use App\Form\CarouselType;
use App\Repository\CarouselRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/carousel', name: 'admin_carousel_')]
#[IsGranted('ROLE_USER')]
class CarouselController extends AbstractController
{
    
    public function __construct(private CarouselRepository $carouselRepository)
    {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('carousel/index.html.twig', [
            'carousels' => $this->carouselRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $carousel = new Carousel();
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->carouselRepository->save($carousel, true);

            return $this->redirectToRoute('admin_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carousel/new.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carousel $carousel): Response
    {
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->carouselRepository->save($carousel, true);

            return $this->redirectToRoute('admin_carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carousel/edit.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Carousel $carousel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carousel->getId(), $request->request->get('_token'))) {
            $oldId = $carousel->getId();
            $this->carouselRepository->remove($carousel, true);

            try{
                $basePath = $this->getParameter('upload_carousel_images_directory') . DIRECTORY_SEPARATOR . $oldId;
                $fs = new Filesystem();
                $fs->remove($basePath);

            }catch (\Exception $ex){
                // do nothing on delete folder errors
            }

        }

        return $this->redirectToRoute('admin_carousel_index', [], Response::HTTP_SEE_OTHER);
    }
}
