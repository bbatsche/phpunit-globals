<?php

declare(strict_types=1);

namespace Zalas\PHPUnit\Globals;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use ReflectionAttribute;
use ReflectionClass;
use Zalas\PHPUnit\Globals\Attributes\GlobalVarAttribute;
use Zalas\PHPUnit\Globals\Subscriber\Finished;
use Zalas\PHPUnit\Globals\Subscriber\PreparationStarted;
use Zalas\PHPUnit\Globals\Subscriber\Started;

final class AttributeExtension implements Extension
{
    private array $server;
    private array $env;
    private array $getenv;

    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters,
    ): void {
        $facade->registerSubscribers(
            new Started($this),
            new PreparationStarted($this),
            new Finished($this),
        );
    }

    public function backupGlobals(): void
    {
        $this->server = $_SERVER;
        $this->env = $_ENV;
        $this->getenv = \getenv();
    }

    public function restoreGlobals(): void
    {
        $_SERVER = $this->server;
        $_ENV = $this->env;

        foreach (\array_diff_assoc($this->getenv, \getenv()) as $name => $value) {
            \putenv(\sprintf('%s=%s', $name, $value));
        }
        foreach (\array_diff_assoc(\getenv(), $this->getenv) as $name => $value) {
            \putenv($name);
        }
    }

    public function readAttributes(TestMethod $test): void
    {
        $reflectionClass = new ReflectionClass($test->className());
        $reflectionMethod = $reflectionClass->getMethod($test->methodName());

        $attributes = \array_merge(
            $reflectionClass->getAttributes(GlobalVarAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
            $reflectionMethod->getAttributes(GlobalVarAttribute::class, ReflectionAttribute::IS_INSTANCEOF),
        );

        foreach ($attributes as $globalVar) {
            $globalVar->newInstance()->apply();
        }
    }
}
