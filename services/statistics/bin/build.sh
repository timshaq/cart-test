#!/bin/bash
set -e

COMPOSER_COMMAND='composer install'

if [ "$ENV" = 'prod' ]; then
  sh -c "$COMPOSER_COMMAND --no-interaction --no-dev --optimize-autoloader";
else
  sh -c "$COMPOSER_COMMAND --no-interaction";
fi

sh -c "php bin/console cache:clear --no-debug";
sh -c "php bin/console cache:warmup";
