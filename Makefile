default: build

build: test
.PHONY: build

update:
	composer update
.PHONY: update

update-min:
	composer update --prefer-lowest
.PHONY: update-min

update-no-dev:
	composer update --no-dev
.PHONY: update-no-dev

test: cs phpunit
.PHONY: test

test-min: update-min cs phpunit
.PHONY: test-min

test-package: vendor/bin/phpunit build/zalas-phpunit-globals-extension.phar
	vendor/bin/phpunit -c tests/phar/phpunit.xml
.PHONY: test-package

cs: tools/php-cs-fixer
	tools/php-cs-fixer --dry-run --no-interaction --ansi -v --diff fix
.PHONY: cs

cs-fix: tools/php-cs-fixer
	tools/php-cs-fixer --no-interaction --ansi fix
.PHONY: cs-fix

phpunit: vendor/bin/phpunit
	vendor/bin/phpunit
.PHONY: phpunit

clean:
	rm -rf composer.lock build vendor
	find tools -not -path '*/\.*' -type f -delete
.PHONY: clean

build/zalas-phpunit-globals-extension.phar: tools/box box.json.dist $(wildcard src/*.php) $(wildcard src/**/*.php)
	$(eval VERSION=$(shell git describe --abbrev=0 --tags 2> /dev/null | sed -e 's/^v//' || echo 'dev'))
	@rm -rf build/phar && mkdir -p build/phar

	cp -r src LICENSE composer.json build/phar
	sed -e 's/@@version@@/$(VERSION)/g' manifest.xml.in > build/phar/manifest.xml

	cd build/phar && \
	  composer remove phpunit/phpunit --no-update && \
	  composer config platform.php 8.1 && \
	  composer update --no-dev -o -a

	tools/box compile

	@rm -rf build/phar

package: build/zalas-phpunit-globals-extension.phar
.PHONY: package

vendor: composer.json $(wildcard composer.lock)
	composer install

vendor/bin/phpunit: vendor

tools/php-cs-fixer:
	curl -Ls http://cs.symfony.com/download/php-cs-fixer-v3.phar -o tools/php-cs-fixer && chmod +x tools/php-cs-fixer

tools/box:
	curl -Ls https://github.com/humbug/box/releases/download/4.1.0/box.phar -o tools/box && chmod +x tools/box
