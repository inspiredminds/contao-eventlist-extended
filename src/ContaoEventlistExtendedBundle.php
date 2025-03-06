<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoEventlistExtendedBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoEventlistExtendedBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
