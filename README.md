# Electricbass

My long time hobby project 'bout bass playing an more.

<https://www.electricbass.ch>

## Static Code Analysis

To analyse code base using PHPStan run the following command.

~~~bash
vendor/bin/phpstan analyse --memory-limit=256M
~~~

Or

~~~bash
docker-compose run php vendor/bin/phpstan analyse --memory-limit=256M
~~~

## PHP CodeSniffer

Analyse

    vendor/bin/phpcs

Fix

    vendor/bin/phpcbf
