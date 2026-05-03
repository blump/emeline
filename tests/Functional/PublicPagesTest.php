<?php

namespace App\Tests\Functional;

use App\Tests\Support\RefreshDatabaseTrait;
use App\Tests\Support\TestFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicPagesTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;

    /**
     * @var array<string, int>
     */
    private array $ids;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->refreshDatabase();
        $this->ids = TestFixtures::load($this->getEntityManager());
        $this->client->disableReboot();
    }

    public function testAccueilIsAccessible(): void
    {
        $crawler = $this->client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Bienvenue sur le site de MediaTek86', $crawler->filter('body')->text());
    }

    public function testFormationsCanBeSortedByTitle(): void
    {
        $crawler = $this->client->request('GET', '/formations/tri/title/ASC');

        self::assertResponseIsSuccessful();

        $titles = $crawler->filter('tbody tr td h5')->each(static fn ($node) => trim($node->text()));

        self::assertSame(['Excel debutant', 'Symfony 6', 'Word avance'], $titles);
    }

    public function testFormationsCanBeFilteredByTitle(): void
    {
        $crawler = $this->client->request('POST', '/formations/recherche/title', [
            'recherche' => 'Excel',
        ]);

        self::assertResponseIsSuccessful();

        $titles = $crawler->filter('tbody tr td h5')->each(static fn ($node) => trim($node->text()));

        self::assertSame(['Excel debutant'], $titles);
    }

    public function testPlaylistsCanBeSortedByFormationCount(): void
    {
        $crawler = $this->client->request('GET', '/playlists/tri/formations/DESC');

        self::assertResponseIsSuccessful();

        $names = $crawler->filter('tbody tr td h5')->each(static fn ($node) => trim($node->text()));
        $counts = $crawler->filter('tbody tr td:nth-child(3)')->each(static fn ($node) => trim($node->text()));

        self::assertSame(['Bureautique', 'Developpement'], $names);
        self::assertSame(['2', '1'], $counts);
    }
}
