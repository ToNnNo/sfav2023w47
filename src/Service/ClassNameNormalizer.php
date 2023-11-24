<?php
declare(strict_types=1);

namespace App\Service;

use function Symfony\Component\String\u;

class ClassNameNormalizer
{

    public function normalize(string $name): string
    {
        return u($name)->ascii()->camel()->title()->toString();
    }
}
