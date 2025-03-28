# cart

## Start
### Configure
- unsample *.sample files
- set .env parameters (DB, Kafka DSN, JWT, etc.)

### Build image and run project
```bash
docker compose build
```
```bash
docker compose up -d
```

### Create jwt keys
```bash
docker exec -it cart bash
```
```bash
php bin/console lexik:jwt:generate-keypair
```

### Set similar ENV and APP_ENVs and run build
```bash
bin/build
```


## Tests
```bash
php bin/phpunit tests
```

## Run consumer
```bash
php bin/console messenger:consume
```
