<?php

namespace App\Tests\Support;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Doctrine\ORM\EntityManagerInterface;

final class TestFixtures
{
    /**
     * @return array<string, int>
     */
    public static function load(EntityManagerInterface $entityManager): array
    {
        $playlistBureautique = (new Playlist())
            ->setName('Bureautique')
            ->setDescription('Playlist bureautique');

        $playlistDeveloppement = (new Playlist())
            ->setName('Developpement')
            ->setDescription('Playlist developpement');

        $categorieExcel = (new Categorie())->setName('Excel');
        $categorieWord = (new Categorie())->setName('Word');
        $categorieSymfony = (new Categorie())->setName('Symfony');

        $formationExcel = (new Formation())
            ->setTitle('Excel debutant')
            ->setPublishedAt(new \DateTime('2024-01-10'))
            ->setVideoId('excel001')
            ->setDescription('Formation Excel')
            ->setPlaylist($playlistBureautique)
            ->addCategory($categorieExcel);

        $formationWord = (new Formation())
            ->setTitle('Word avance')
            ->setPublishedAt(new \DateTime('2024-02-15'))
            ->setVideoId('word001')
            ->setDescription('Formation Word')
            ->setPlaylist($playlistBureautique)
            ->addCategory($categorieWord);

        $formationSymfony = (new Formation())
            ->setTitle('Symfony 6')
            ->setPublishedAt(new \DateTime('2024-03-20'))
            ->setVideoId('symfony001')
            ->setDescription('Formation Symfony')
            ->setPlaylist($playlistDeveloppement)
            ->addCategory($categorieSymfony);

        foreach ([
            $playlistBureautique,
            $playlistDeveloppement,
            $categorieExcel,
            $categorieWord,
            $categorieSymfony,
            $formationExcel,
            $formationWord,
            $formationSymfony,
        ] as $entity) {
            $entityManager->persist($entity);
        }

        $entityManager->flush();
        $entityManager->clear();

        return [
            'playlistBureautiqueId' => $playlistBureautique->getId(),
            'playlistDeveloppementId' => $playlistDeveloppement->getId(),
            'categorieExcelId' => $categorieExcel->getId(),
            'categorieWordId' => $categorieWord->getId(),
            'categorieSymfonyId' => $categorieSymfony->getId(),
            'formationExcelId' => $formationExcel->getId(),
            'formationWordId' => $formationWord->getId(),
            'formationSymfonyId' => $formationSymfony->getId(),
        ];
    }
}
