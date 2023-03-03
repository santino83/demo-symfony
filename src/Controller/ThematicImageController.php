<?php

namespace App\Controller;

use App\Entity\Thematic;
use App\Entity\ThematicImage;
use App\Form\ThematicImageType;
use App\Repository\ThematicImageRepository;
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

#[Route('/admin/thematic/{id}/images', name: 'admin_thematic_images_')]
#[IsGranted('ROLE_USER')]
class ThematicImageController extends AbstractController
{

    public function __construct(private ThematicImageRepository $repository)
    {
    }

    #[Route('/', name: 'show', methods: ['GET', 'POST'])]
    public function show(Thematic $thematic, Request $request): Response
    {
        $thematicImage = new ThematicImage();
        $thematicImage->setThematic($thematic)
            ->setPosition($thematic->getThematicImages()->count() + 1);

        $basePath = $this->getParameter('upload_thematic_images_directory') . DIRECTORY_SEPARATOR . $thematic->getId();

        $form = $this->createForm(ThematicImageType::class, $thematicImage);
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

                    $thematicImage->setImagePath($newFileName);
                    $this->repository->append($thematicImage);

                    return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);

                } catch (FileException|\Exception $e) {
                    $form->get('image')->addError(new FormError("Cannot Upload Image. Please try again"));
                }

            } else {
                $form->get('image')->addError(new FormError("An Image is required"));
            }

        }

        return $this->render('thematic_images/show.html.twig', [
            'thematic' => $thematic,
            'form' => $form
        ]);
    }

    #[Route('/delete/{imageId}', name: 'delete', methods: ['POST'])]
    #[Entity('thematicImage', expr: 'repository.find(imageId)')]
    public function delete(Thematic $thematic, ThematicImage $thematicImage, Request $request): Response
    {
        if ($thematicImage->getThematic()->getId() !== $thematic->getId()) {
            return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete' . $thematicImage->getId(), $request->request->get('_token'))) {
            $this->repository->deleteFromPosition($thematicImage);

            try {
                $basePath = $this->getParameter('upload_thematic_images_directory') . DIRECTORY_SEPARATOR . $thematic->getId();
                $fs = new Filesystem();
                $fs->remove($basePath . DIRECTORY_SEPARATOR . $thematicImage->getImagePath());

            } catch (\Exception $ex) {
                // do nothing on deleting file errors
            }

        }

        return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/change/{imageId}/up', name: 'change_up', methods: ['POST'])]
    #[Entity('thematicImage', expr: 'repository.find(imageId)')]
    public function changePositionUp(Thematic $thematic, ThematicImage $thematicImage, Request $request): Response
    {
        if ($thematicImage->getThematic()->getId() !== $thematic->getId()) {
            return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('changeUp' . $thematicImage->getId(), $request->request->get('_token'))) {
            $this->repository->moveUp($thematicImage);
        }

        return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/change/{imageId}/down', name: 'change_down', methods: ['POST'])]
    #[Entity('thematicImage', expr: 'repository.find(imageId)')]
    public function changePositionDown(Thematic $thematic, ThematicImage $thematicImage, Request $request): Response
    {
        if ($thematicImage->getThematic()->getId() !== $thematic->getId()) {
            return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('changeDown' . $thematicImage->getId(), $request->request->get('_token'))) {
            $this->repository->moveDown($thematicImage);
        }

        return $this->redirectToRoute('admin_thematic_images_show', ['id' => $thematic->getId()], Response::HTTP_SEE_OTHER);
    }


}