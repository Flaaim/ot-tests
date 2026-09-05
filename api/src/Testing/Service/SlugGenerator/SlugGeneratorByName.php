<?php

declare(strict_types=1);

namespace App\Testing\Service\SlugGenerator;

use DomainException;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class SlugGeneratorByName implements SlugGeneratorInterface
{
    public function generate(string $value): string
    {
        $slugger = new AsciiSlugger();

        $slug = $slugger->slug($value)->lower()->toString();

        if ('' === $slug) {
            throw new DomainException('Cannot generate slug from the given name.');
        }

        return $slug;
    }
}
