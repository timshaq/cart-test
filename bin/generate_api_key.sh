#!/bin/bash

docker exec -it -u www-data cart php -r "echo bin2hex(random_bytes(32)).PHP_EOL;"
