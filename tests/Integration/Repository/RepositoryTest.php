<?php

namespace App\Tests\Integration\Repository;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Tests\Support\RefreshDatabaseTrait;
use App\Tests\Support\TestFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @var array<string, int>
     */
    private array $ids;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->refreshDatabase();
        $this->ids = TestFixtures::load($this->getEntityManager());
    }

    public function testFindAllLastedReturnsMostRecentFormations(): void
    {
        /** @var FormationRepository $repository */
        $repository = static::getContainer()->get(FormationRepository::class);

        $formations = $repository->findAllLasted(2);

        self::assertCount(2, $formations);
        self::assertSame('Symfony 6', $formations[0]->getTitle());
        self::assertSame('Word avance', $formations[1]->getTitle());
    }

    public function testFindFormationByTitleContainsValue(): void
    {
        /** @var FormationRepository $repository */
        $repository = static::getContainer()->get(FormationRepository::class);

        $formations = $repository->findByContainValue('title', 'Excel');

        self::assertCount(1, $formations);
        self::assertSame('Excel debutant', $formations[0]->getTitle());
    }

    public function testFindFormationByCategoryId(): void
    {
        /** @var FormationRepository $repository */
        $repository = static::getContainer()->get(FormationRepository::class);

        $formations = $repository->findByContainValue('id', (string) $this->ids['categorieSymfonyId'], 'categories');

        self::assertCount(1, $formations);
        self::assertSame('Symfony 6', $formations[0]->getTitle());
    }

    public function testFindPlaylistsOrderedByFormationCount(): void
    {
        /** @var PlaylistRepository $repository */
        $repository = static::getContainer()->get(PlaylistRepository::class);

        $playlists = $repository->findAllOrderByFormationCount('DESC');

        self::assertCount(2, $playlists);
        self::assertSame('Bureautique', $playlists[0]->getName());
        self::assertSame(2, $playlists[0]->getNombreFormations());
    }

    public function testFindCategoriesForOnePlaylist(): void
    {
        /** @var CategorieRepository $repository */
        $repository = static::getContainer()->get(CategorieRepository::class);

        $categories = $repository->findAllForOnePlaylist($this->ids['playlistBureautiqueId']);

        self::assertCount(2, $categories);
        self::assertSame('Excel', $categories[0]->getName());
        self::assertSame('Word', $categories[1]->getName());
    }
}
