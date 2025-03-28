#!/bin/bash

set -e  # Остановить скрипт при ошибке

USER="www-data"
CONTAINERS=("cart")

echo "$ENV"

for CONTAINER in "${CONTAINERS[@]}"; do
  docker exec -u $USER "$CONTAINER" sh -c "bin/build.sh"
done
