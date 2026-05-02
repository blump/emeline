<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/formations', name: 'admin.formations.')]
class AdminFormationController extends AbstractController
{
    public function __construct(
        private readonly FormationRepository $formationRepository
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/formations/index.html.twig', [
            'formations' => $this->formationRepository->findBy([], ['publishedAt' => 'DESC']),
        ]);
    }

    #[Route('/ajout', name: 'new')]
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);

            return $this->redirectToRoute('admin.formations.index');
        }

        return $this->render('admin/formations/new.html.twig', [
            'formationForm' => $form,
        ]);
    }

    #[Route('/{id}/modifier', name: 'edit')]
    public function edit(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if ($formation === null) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formationRepository->add($formation);

            return $this->redirectToRoute('admin.formations.index');
        }

        return $this->render('admin/formations/edit.html.twig', [
            'formation' => $formation,
            'formationForm' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);

        if ($formation === null) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        if ($this->isCsrfTokenValid('delete_formation_'.$formation->getId(), (string) $request->request->get('_token'))) {
            $this->formationRepository->remove($formation);
        }

        return $this->redirectToRoute('admin.formations.index');
    }
}
