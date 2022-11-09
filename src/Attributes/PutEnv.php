<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_CLASS|Attribute::TARGET_METHOD)]
final class PutEnv extends SetGlobalVarAttribute
{
    public function apply()
    {
        \putenv(\sprintf('%s=%s', $this->name, $this->value));
    }
}
