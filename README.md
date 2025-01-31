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

### Enter in container bash
```bash
docker exec -it cart bash
```

#### Install packages
```bash
composer install
```

#### Create jwt keys
```bash
php bin/console lexik:jwt:generate-keypair
```

#### Migrate db
```bash
php bin/console doctrine:migrations:migrate
```
