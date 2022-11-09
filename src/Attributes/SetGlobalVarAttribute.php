<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Attributes;

abstract class SetGlobalVarAttribute extends GlobalVarAttribute
{
    public function __construct(
        string $name,
        protected string $value,
    ) {
        parent::__construct($name);
    }
}
