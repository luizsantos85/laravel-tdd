# laravel-tdd

Projeto de estudo em Laravel com foco em TDD e execução via Docker Compose. /
This is a Laravel study project focused on TDD and running with Docker Compose.

## Pré-requisitos / Prerequisites

1. Docker e Docker Compose / Docker & Docker Compose
1. (Opcional) Acesso ao container `app` para rodar comandos internos / (Optional) Access to the `app` container to run internal commands

## Como rodar (instalação rápida) / Quickstart

1. Suba os containers (rebuild) / Build and start containers:

```bash
docker compose up -d --build
```

1. Entre no container `app` para instalar dependências e configurar a aplicação / Enter the `app` container to install dependencies and configure the app:

```bash
docker compose exec app bash
```

1. Dentro do container, instale dependências / Inside the container, install dependencies:

```bash
composer install --no-interaction --prefer-dist
```

1. Gere a app key / Generate the app key:

```bash
php artisan key:generate
```

1. Rode as migrations / Run migrations:

```bash
php artisan migrate --force
```

1. (Opcional) Seeders / (Optional) Seeders:

```bash
php artisan db:seed
```

1. Rode a suíte de testes / Run tests:

```bash
php artisan test
```

## Serviços no ambiente Docker / Services in the Docker setup

1. MySQL (`db`): porta mapeada `3307:3306` — acesse internamente como host `db:3306`. No `.env` usamos `DB_HOST=db`.
1. phpMyAdmin: disponível em `http://localhost:8080` (PMA aponta para `db`).
1. Nginx: porta mapeada `8989:80` — acesse a aplicação em `http://localhost:8989`.
1. Redis: serviço `redis` (host `redis`) — usado pelo sistema de queue/cache.
1. Queue: depende da configuração `QUEUE_CONNECTION=database` no `.env`; para processar jobs use `php artisan queue:work` dentro do container `app`.

## Comandos úteis / Useful commands

1. Entrar no container app / Enter the app container:

```bash
docker compose exec app bash
```

1. Rodar composer install (sem shell):

```bash
docker compose exec app composer install --no-interaction --prefer-dist
```

1. Gerar app key e migrar (sem shell):

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
```

1. Ver logs do nginx e app / Follow nginx and app logs:

```bash
docker compose logs -f nginx app
```

1. Acessos rápidos / Quick access:

-   phpMyAdmin: `http://localhost:8080`
-   MySQL internal: `db:3306` (mapped to host `3307`)
-   Nginx: `http://localhost:8989`
-   Redis: `redis:6379`

## Troubleshooting / Notas

1. Se ocorrer erro sobre traits ou pacotes faltantes (ex.: `Laravel\Sanctum\HasApiTokens`), instale dependências com composer:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate --force
```

1. Para comandos de administração locais, prefira rodar dentro do container `app` para evitar diferenças de versão do PHP/ext.

## Parar e limpar / Stop and clean

1. Parar / Stop:

```bash
docker compose down
```

1. Remover volumes (apaga dados) / Remove volumes (removes data):

```bash
docker compose down -v
```

---
