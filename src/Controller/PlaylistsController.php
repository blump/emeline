<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsController extends AbstractController
{

    private readonly PlaylistRepository $playlistRepository;

    private readonly FormationRepository $formationRepository;

    private readonly CategorieRepository $categorieRepository;

    public function __construct(
        PlaylistRepository  $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    )
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        switch ($champ) {
            case 'name':
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case 'formations':
                $playlists = $this->playlistRepository->findAllOrderByFormationCount($ordre);
                break;
            default:
                throw $this->createNotFoundException('Champ de tri inconnu.');
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table = ''): Response
    {
        $valeur = (string)$request->request->get('recherche', '');
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne(int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if ($playlist === null) {
            throw $this->createNotFoundException('Playlist introuvable.');
        }

        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }

}
