<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
final class Server extends SetGlobalVarAttribute
{
    public function apply()
    {
        $_SERVER[$this->name] = $this->value;
    }
}
