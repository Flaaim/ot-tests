<?php

declare(strict_types=1);

namespace App\Testing\Service\SlugGenerator;

interface SlugGeneratorInterface
{
    public function generate(string $value): string;
}
