<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals\Subscriber;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\PreparationStarted as TestPreparationStarted;
use PHPUnit\Event\Test\PreparationStartedSubscriber;

final class PreparationStarted extends EventSubscriber implements PreparationStartedSubscriber
{
    public function notify(TestPreparationStarted $event): void
    {
        $test = $event->test();

        if ($test->isTestMethod()) {
            \assert($test instanceof TestMethod);

            $this->extension->readAttributes($test);
        }
    }
}
