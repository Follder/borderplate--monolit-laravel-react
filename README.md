# User flow

## Mount container

```
docker compose up -d
```

## Unmount container
```
docker compose down
```
## Go to container
```
docker compose exec app sh
```

## Run php stun on local machine

```bash
  docker compose exec -t app  ./vendor/bin/phpstan analyse
```
Якщо використовуєте Docker:

# Запустити контейнери
docker-compose up -d

# Зайти в контейнер та виконати команди
docker exec -it yaremche-app bash

# Всередині контейнера:
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build

