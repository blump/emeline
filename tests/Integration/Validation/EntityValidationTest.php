<?php

namespace App\Tests\Integration\Validation;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    public function testFormationValidationRules(): void
    {
        $formation = new Formation();
        $formation->setTitle('');
        $formation->setVideoId(str_repeat('a', 21));

        $violations = $this->validator->validate($formation);

        self::assertGreaterThanOrEqual(4, $violations->count());
    }

    public function testPlaylistNameIsRequired(): void
    {
        $playlist = new Playlist();
        $playlist->setName('');

        $violations = $this->validator->validate($playlist);

        self::assertGreaterThan(0, $violations->count());
    }

    public function testCategorieNameIsRequired(): void
    {
        $categorie = new Categorie();
        $categorie->setName('');

        $violations = $this->validator->validate($categorie);

        self::assertGreaterThan(0, $violations->count());
    }

    public function testValidFormationHasNoViolation(): void
    {
        $playlist = (new Playlist())->setName('Playlist valide');

        $formation = (new Formation())
            ->setTitle('Formation valide')
            ->setPublishedAt(new \DateTimeImmutable('2024-05-13'))
            ->setVideoId('video123')
            ->setPlaylist($playlist);

        $violations = $this->validator->validate($formation);

        self::assertCount(0, $violations);
    }
}
