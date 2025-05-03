#!/bin/bash
set -e

COMPOSER_COMMAND='composer install'
MIGRATION_COMMAND='php bin/console doctrine:migrations:migrate --all-or-nothing --no-interaction'
CONSUMERS=("product_consumer" "order_status_consumer")

if [ "$ENV" = 'prod' ]; then
  sh -c "$COMPOSER_COMMAND --no-interaction";
  sh -c "$MIGRATION_COMMAND";
  sh -c "$MIGRATION_COMMAND --env=test";

  sh -c "php bin/phpunit tests --log-junit var/log/phpunit-report.xml --coverage-text";

  sh -c "$COMPOSER_COMMAND --no-interaction --no-dev --optimize-autoloader";

  for CONSUMER in "${CONSUMERS[@]}"; do
    sh -c "nohup php bin/console messenger:consume $CONSUMER --no-interaction &"
  done

else
  sh -c "$COMPOSER_COMMAND --no-interaction";
  sh -c "$MIGRATION_COMMAND";
  sh -c "$MIGRATION_COMMAND --env=test";
fi

sh -c "php bin/console cache:clear --no-debug";
sh -c "php bin/console cache:warmup";
