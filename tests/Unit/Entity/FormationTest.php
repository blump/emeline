<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{
    public function testGetPublishedAtStringReturnsFormattedDate(): void
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTimeImmutable('2024-05-13'));

        self::assertSame('13/05/2024', $formation->getPublishedAtString());
    }
}
