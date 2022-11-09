<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
final class UnsetEnv extends GlobalVarAttribute
{
    public function apply()
    {
        unset($_ENV[$this->name]);
    }
}
