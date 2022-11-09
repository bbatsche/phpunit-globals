<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Attributes;

abstract class GlobalVarAttribute
{
    public function __construct(
        protected string $name
    ) {
    }

    abstract public function apply();
}
