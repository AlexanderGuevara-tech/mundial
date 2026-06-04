# Polla Laravel — Docker Production Setup

Containerized deployment for the Polla Mundial 2026 prediction game.

## Prerequisites

- **Ubuntu 22.04+** (or any Linux distribution with Docker support)
- **Docker Engine** 24+ ([install guide](https://docs.docker.com/engine/install/ubuntu/))
- **Docker Compose** v2 (included with Docker Desktop or install plugin)
- **Git**
- A domain name with DNS pointing to your server (for SSL)

## Step 1: Clone the Repository

```bash
git clone <repository-url> /var/www/polla
cd /var/www/polla
```

## Step 2: Environment Setup

```bash
cp .env.example .env
```

Edit `.env` with your production values:

```bash
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate with: php artisan key:generate>
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=polla
DB_USERNAME=polla
DB_PASSWORD=<secure-password>

REDIS_HOST=redis
REDIS_PORT=6379

CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

Generate the application key:

```bash
php artisan key:generate
```

> **Note**: If PHP is not installed locally, you can generate the key inside the container after the first build: `docker compose run --rm app php artisan key:generate`.

## Step 3: Docker Build & Run

Build and start all services:

```bash
docker compose up -d --build
```

Verify all containers are healthy:

```bash
docker compose ps
```

## Step 4: Database Setup

Run migrations to create the database schema:

```bash
docker compose exec app php artisan migrate --force
```

If you need seed data:

```bash
docker compose exec app php artisan db:seed --force
```

## Step 5: Storage Link

Create the storage symlink:

```bash
docker compose exec app php artisan storage:link
```

## Common Commands

| Action                | Command                                               |
|-----------------------|-------------------------------------------------------|
| View logs             | `docker compose logs app --tail=50 -f`               |
| Rebuild containers    | `docker compose up -d --build`                        |
| Stop services         | `docker compose down`                                 |
| Restart services      | `docker compose restart`                              |
| Shell into app        | `docker compose exec app bash`                        |
| Shell into MySQL      | `docker compose exec db mysql -u polla -p`           |
| Run artisan command   | `docker compose exec app php artisan <command>`       |
| Check health endpoint | `curl http://localhost/health`                        |
| Prune old images      | `docker image prune -f`                               |

## Troubleshooting

### Permissions Issues

If you see "Permission denied" errors in `storage/` or `bootstrap/cache/`:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Port Conflicts

If port 80 or 3306 is already in use:

1. Change the host port in `docker-compose.yml`:
   ```yaml
   ports:
     - "8080:80"   # Use port 8080 instead of 80
   ```
2. Update `APP_URL` in `.env` accordingly.

### Database Connection Errors

If the app cannot connect to MySQL:

1. Verify MySQL is healthy: `docker compose ps`
2. Check MySQL logs: `docker compose logs db`
3. Ensure `.env` has `DB_HOST=mysql` (the Docker service name, not `127.0.0.1`)
4. Verify the database exists: `docker compose exec db mysql -u polla -p -e "SHOW DATABASES;"`

### Container Keeps Restarting

Check the logs for errors:

```bash
docker compose logs app --tail=50
```

Common causes:
- Missing `.env` variables
- MySQL not ready before app starts (check `depends_on` health check)
- Permission issues on `storage/` or `bootstrap/cache/`

### Redis Connection Issues

Ensure `.env` has `REDIS_HOST=redis` (the Docker service name). Verify Redis is running:

```bash
docker compose exec redis redis-cli ping
# Should reply: PONG
```

### SSL / HTTPS

For production SSL, run Certbot on the host:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

Certbot auto-renews via systemd timer. Verify with:

```bash
sudo certbot renew --dry-run
```

## Architecture

```
                         polla-network (bridge)
                               │
    Internet ──► nginx:80/443 ──► app:9000 (PHP-FPM)
                    │                  │
                    │            Supervisor
                    │            ├── php-fpm
                    │            └── queue:work
                    │                  │
                    │            ┌─────┴─────┐
                    │            ▼           ▼
                    │         mysql:3306  redis:6379
                    │
             Host cron ──► backup.sh ──► /var/backups/polla/
```
