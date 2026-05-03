<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories', name: 'admin.categories.')]
class AdminCategorieController extends AbstractController
{
    public function __construct(
        private readonly CategorieRepository $categorieRepository
    ) {
    }

    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categorieRepository->add($categorie);

            return $this->redirectToRoute('admin.categories.index');
        }

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $this->categorieRepository->findBy([], ['name' => 'ASC']),
            'categorieForm' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);

        if ($categorie === null) {
            throw $this->createNotFoundException('Categorie introuvable.');
        }

        foreach ($categorie->getFormations()->toArray() as $formation) {
            $categorie->removeFormation($formation);
        }

        $this->categorieRepository->remove($categorie);

        return $this->redirectToRoute('admin.categories.index');
    }
}
