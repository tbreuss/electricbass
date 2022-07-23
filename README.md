# Electricbass

> My long time hobby project about bass playing and more.

<https://www.electricbass.ch>

## Note

This project does not contain a database. 
Without one, it cannot be started locally.
However, the project can serve as an illustrative example for a Yii2 web application.
I hope this is helpful for one or the other.

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

## Build Asset Files

Build CSS

    npm run build-css

Build minified CSS

    npm run build-min-css

Build JavaScript

    npm run build-js

Build minified JavaScript

    npm run build-min-js

Generated asset files are stored in folder `/assets/app/dist`.
