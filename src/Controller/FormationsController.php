<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsController extends AbstractController
{

    private readonly FormationRepository $formationRepository;

    private readonly CategorieRepository $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort(string $champ, string $ordre, string $table = ''): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table = ''): Response
    {
        $valeur = (string)$request->request->get('recherche', '');
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne(int $id): Response
    {
        $formation = $this->formationRepository->find($id);

        if ($formation === null) {
            throw $this->createNotFoundException('Formation introuvable.');
        }

        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }

}
