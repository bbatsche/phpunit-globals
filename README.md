# PHPUnit Globals

Allows to use attributes to define global variables in PHPUnit test cases.

[![Build Status](https://github.com/jakzal/phpunit-globals/actions/workflows/build.yml/badge.svg)](https://github.com/jakzal/phpunit-globals/actions/workflows/build.yml)

Supported attributes:

 * `Zalas\PHPUnit\Globals\Attributes\Env` for `$_ENV`
 * `Zalas\PHPUnit\Globals\Attributes\Server` for `$_SERVER`
 * `Zalas\PHPUnit\Globals\Attributes\PutEnv` for [`putenv()`](http://php.net/putenv)

Global variables are set before each test case is executed,
and brought to the original state after each test case has finished.
The same applies to `putenv()`/`getenv()` calls.

## Installation

### Composer

```bash
composer require --dev zalas/phpunit-globals
```

### Phar

The extension is also distributed as a PHAR, which can be downloaded from the most recent
[Github Release](https://github.com/jakzal/phpunit-globals/releases).

Put the extension in your PHPUnit extensions directory.
Remember to instruct PHPUnit to load extensions in your `phpunit.xml`:

```xml
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10/phpunit.xsd"
         extensionsDirectory="tools/phpunit.d"
>
</phpunit>
```

## Usage

Enable the globals attribute extension in your PHPUnit configuration:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd"
         bootstrap="vendor/autoload.php">

    <!-- ... -->

    <extensions>
        <bootstrap class="Zalas\PHPUnit\Globals\AttributeExtension" />
    </extensions>
</phpunit>
```

Make sure the `AttributeExtension` is registered before any other extensions that might depend on global variables.

Global variables can now be defined in attributes:

```php
use PHPUnit\Framework\TestCase;
use Zalas\PHPUnit\Globals\Attributes\Env;
use Zalas\PHPUnit\Globals\Attributes\Server;
use Zalas\PHPUnit\Globals\Attributes\PutEnv;

#[Env('FOO', 'bar')]
class ExampleTest extends TestCase
{
    #[Env('APP_ENV', 'foo')]
    #[Env('APP_DEBUG', '0')]
    #[Server('APP_ENV', 'bar')]
    #[Server('APP_DEBUG', '1')]
    #[PutEnv('APP_HOST', 'localhost')]
    public function test_global_variables()
    {
        $this->assertSame('bar', $_ENV['FOO']);
        $this->assertSame('foo', $_ENV['APP_ENV']);
        $this->assertSame('0', $_ENV['APP_DEBUG']);
        $this->assertSame('bar', $_SERVER['APP_ENV']);
        $this->assertSame('1', $_SERVER['APP_DEBUG']);
        $this->assertSame('localhost', \getenv('APP_HOST'));
    }
}
```

It's also possible to mark a variable as _unset_ so it will not be present in any of the global variables:

```php
use PHPUnit\Framework\TestCase;
use Zalas\PHPUnit\Globals\Attributes\UnsetEnv;
use Zalas\PHPUnit\Globals\Attributes\UnsetServer;
use Zalas\PHPUnit\Globals\Attributes\UnsetGetEnv;

class ExampleTest extends TestCase
{
    #[UnsetEnv('APP_ENV')]
    #[UnsetServer('APP_DEBUG')]
    #[UnsetGetEnv('APP_HOST')]
    public function test_global_variables()
    {
        $this->assertArrayNotHasKey('APP_ENV', $_ENV);
        $this->assertArrayNotHasKey('APP_DEBUG', $_SERVER);
        $this->assertArrayNotHasKey('APP_HOST', \getenv());
    }
}
```

## Updating to PHPUnit 10

When updating from a previous version of this extension that used to work with PHPUnit older than v10,
replace the `<extension />` registration in `phpunit.xml`:

```xml
    <extensions>
        <extension class="Zalas\PHPUnit\Globals\AttributeExtension" />
    </extensions>
```

with the `<bootstrap />` registration:

```xml
    <extensions>
        <bootstrap class="Zalas\PHPUnit\Globals\AttributeExtension" />
    </extensions>
```

## Contributing

Please read the [Contributing guide](CONTRIBUTING.md) to learn about contributing to this project.
Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md).
By participating in this project you agree to abide by its terms.
