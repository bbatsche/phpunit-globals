<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Subscriber;

use Zalas\PHPUnit\Globals\AttributeExtension;

abstract class EventSubscriber
{
    public function __construct(
        protected AttributeExtension $extension,
    ) {
    }
}
