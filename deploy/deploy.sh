#!/bin/bash
set -e

# ============================================================
# Polla Laravel Deploy Script
# Zero-downtime deployment via rsync + docker compose
# ============================================================

# --- Configuration ---
SERVER_USER="${SERVER_USER:-deploy}"
SERVER_IP="${SERVER_IP:-}"
PROJECT_ROOT="${PROJECT_ROOT:-/var/www/polla}"
LOCAL_ROOT="$(cd "$(dirname "$0")/.." && pwd)"

# --- Colors ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

info()  { echo -e "${GREEN}[INFO]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; }

# --- Help ---
usage() {
    echo "Usage: $0 {deploy|rollback|status}"
    echo ""
    echo "Commands:"
    echo "  deploy    Build and deploy the application"
    echo "  rollback  Restore the previous release"
    echo "  status    Show deployment status"
    exit 1
}

if [ $# -eq 0 ]; then
    usage
fi

COMMAND="$1"

# --- Deploy ---
do_deploy() {
    info "Starting deploy..."

    # Validate configuration
    if [ -z "$SERVER_IP" ]; then
        error "SERVER_IP is not set. Export it or set in script config."
        exit 1
    fi

    # 1. Build assets locally
    info "Building frontend assets..."
    cd "$LOCAL_ROOT"
    npm install
    npm run build

    # 2. Rsync code to server
    info "Syncing code to server..."
    rsync -avz --delete \
        --exclude='vendor/' \
        --exclude='node_modules/' \
        --exclude='.env' \
        --exclude='storage/framework/cache/data/*' \
        --exclude='storage/logs/*' \
        --exclude='storage/framework/views/*' \
        --exclude='storage/framework/sessions/*' \
        --exclude='.git/' \
        --exclude='deploy/ssl/' \
        -e ssh \
        "$LOCAL_ROOT/" \
        "${SERVER_USER}@${SERVER_IP}:${PROJECT_ROOT}/"

    # 3. Deploy via SSH
    info "Running remote deploy..."
    ssh "${SERVER_USER}@${SERVER_IP}" << 'EOF'
        cd /var/www/polla

        echo "[INFO] Starting Docker containers..."
        docker compose up -d --build --remove-orphans

        echo "[INFO] Running migrations..."
        docker compose exec -T app php artisan migrate --force

        echo "[INFO] Optimizing Laravel..."
        docker compose exec -T app php artisan optimize

        echo "[INFO] Creating storage link..."
        docker compose exec -T app php artisan storage:link

        echo "[INFO] Pruning old images..."
        docker image prune -f

        echo "[SUCCESS] Deploy complete!"
EOF

    info "Deploy finished successfully."
}

# --- Rollback ---
do_rollback() {
    info "Rolling back to previous release..."

    if [ -z "$SERVER_IP" ]; then
        error "SERVER_IP is not set. Export it or set in script config."
        exit 1
    fi

    ssh "${SERVER_USER}@${SERVER_IP}" << 'EOF'
        cd /var/www/polla

        # Check if previous image exists
        PREV_IMAGE=$(docker images --format "{{.Repository}}:{{.Tag}}" | grep polla-app | head -2 | tail -1)

        if [ -z "$PREV_IMAGE" ]; then
            echo "[ERROR] No previous image found for rollback."
            exit 1
        fi

        echo "[INFO] Rolling back to image: $PREV_IMAGE"
        docker compose up -d --build

        echo "[SUCCESS] Rollback complete."
EOF
}

# --- Status ---
do_status() {
    if [ -z "$SERVER_IP" ]; then
        error "SERVER_IP is not set."
        exit 1
    fi

    ssh "${SERVER_USER}@${SERVER_IP}" << 'EOF'
        cd /var/www/polla

        echo "=== Container Status ==="
        docker compose ps

        echo ""
        echo "=== Recent Logs (app) ==="
        docker compose logs app --tail=20

        echo ""
        echo "=== Health Check ==="
        curl -s -o /dev/null -w "HTTP %{http_code}" http://localhost/health || echo "unreachable"
        echo ""
EOF
}

# --- Main ---
case "$COMMAND" in
    deploy)
        do_deploy
        ;;
    rollback)
        do_rollback
        ;;
    status)
        do_status
        ;;
    *)
        usage
        ;;
esac
