<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/playlists', name: 'admin.playlists.')]
class AdminPlaylistController extends AbstractController
{
    public function __construct(
        private readonly PlaylistRepository $playlistRepository
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $this->playlistRepository->findAllOrderByName('ASC'),
        ]);
    }

    #[Route('/ajout', name: 'new')]
    public function new(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);

            return $this->redirectToRoute('admin.playlists.index');
        }

        return $this->render('admin/playlists/new.html.twig', [
            'playlistForm' => $form,
        ]);
    }

    #[Route('/{id}/modifier', name: 'edit')]
    public function edit(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if ($playlist === null) {
            throw $this->createNotFoundException('Playlist introuvable.');
        }

        $form = $this->createForm(PlaylistType::class, $playlist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->playlistRepository->add($playlist);

            return $this->redirectToRoute('admin.playlists.index');
        }

        return $this->render('admin/playlists/edit.html.twig', [
            'playlist' => $playlist,
            'playlistForm' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', methods: ['POST'])]
    public function delete(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if ($playlist === null) {
            throw $this->createNotFoundException('Playlist introuvable.');
        }

        foreach ($playlist->getFormations()->toArray() as $formation) {
            $playlist->removeFormation($formation);
        }

        $this->playlistRepository->remove($playlist);

        return $this->redirectToRoute('admin.playlists.index');
    }
}
