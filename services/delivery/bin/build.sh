#!/bin/bash
set -e

COMPOSER_COMMAND='composer install'
MIGRATION_COMMAND='php bin/console doctrine:migrations:migrate --all-or-nothing --no-interaction'

# Install php dependencies via composer
if [ "$ENV" = 'prod' ]; then
  sh -c "$COMPOSER_COMMAND --no-interaction --no-dev --optimize-autoloader";
  sh -c "$MIGRATION_COMMAND";
else
  sh -c "$COMPOSER_COMMAND --no-interaction";
  sh -c "$MIGRATION_COMMAND";
fi

sh -c "php bin/console cache:clear --no-debug";
sh -c "php bin/console cache:warmup";
