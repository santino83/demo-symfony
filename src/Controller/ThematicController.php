<?php

namespace App\Controller;

use App\Entity\Thematic;
use App\Form\ThematicType;
use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/thematic', name: 'admin_thematic_')]
#[IsGranted('ROLE_USER')]
class ThematicController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ThematicRepository $thematicRepository): Response
    {
        return $this->render('thematic/index.html.twig', [
            'thematics' => $thematicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ThematicRepository $thematicRepository): Response
    {
        $thematic = new Thematic();
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thematicRepository->save($thematic, true);

            return $this->redirectToRoute('admin_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('thematic/new.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Thematic $thematic, ThematicRepository $thematicRepository): Response
    {
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thematicRepository->save($thematic, true);

            return $this->redirectToRoute('admin_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('thematic/edit.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Thematic $thematic, ThematicRepository $thematicRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thematic->getId(), $request->request->get('_token'))) {
            $oldId = $thematic->getId();
            $thematicRepository->remove($thematic, true);

            try{
                $basePath = $this->getParameter('upload_thematic_images_directory') . DIRECTORY_SEPARATOR . $oldId;
                $fs = new Filesystem();
                $fs->remove($basePath);

            }catch (\Exception $ex){
                // do nothing on delete folder errors
            }
        }

        return $this->redirectToRoute('admin_thematic_index', [], Response::HTTP_SEE_OTHER);
    }
}
