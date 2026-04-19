<?php

declare(strict_types=1);

namespace Oophp\Contracts;

interface Chain
{
    public function get(): mixed;

    public function __invoke(): mixed;
}
