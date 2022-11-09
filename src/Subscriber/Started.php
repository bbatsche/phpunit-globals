<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Subscriber;

use PHPUnit\Event\TestSuite\Started as TestStarted;
use PHPUnit\Event\TestSuite\StartedSubscriber;

final class Started extends EventSubscriber implements StartedSubscriber
{
    public function notify(TestStarted $event): void
    {
        if ($event->testSuite()->isForTestClass()) {
            $this->extension->backupGlobals();
        }
    }
}
