<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Subscriber;

use PHPUnit\Event\Test\Finished as FinishedEvent;
use PHPUnit\Event\Test\FinishedSubscriber;

final class Finished extends EventSubscriber implements FinishedSubscriber
{
    public function notify(FinishedEvent $event): void
    {
        if ($event->test()->isTestMethod()) {
            $this->extension->restoreGlobals();
        }
    }
}
