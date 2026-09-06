<?php

declare(strict_types=1);

namespace App\Testing\Test\Unit\Entity\Category;

use App\Testing\Entity\Category\Category;
use App\Testing\Entity\Category\CategoryId;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class CategoryTest extends TestCase
{
    public function testCreate(): void
    {
        $parentCat = new Category(
            $id = CategoryId::generate(),
            $name = 'name',
            $description = 'description',
            $slug = 'slug',
        );

        self::assertEquals($id->getValue(), $parentCat->getId()->getValue());
        self::assertEquals($name, $parentCat->getName());
        self::assertEquals($description, $parentCat->getDescription());
        self::assertEquals($slug, $parentCat->getSlug());
        self::assertNull($parentCat->getParentId());
    }
}
