#!/bin/bash

set -e  # Остановить скрипт при ошибке

USER="www-data"
CONTAINERS=(
  "cart"
  "delivery-service"
  "notification-service"
  "product-service"
  "statistics-service"
) #todo: run by user (not by root) -u $USER

echo "$ENV"

for CONTAINER in "${CONTAINERS[@]}"; do
  docker exec "$CONTAINER" sh -c "bin/build.sh"
done
