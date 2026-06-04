#!/bin/bash
set -e

# ============================================================
# Polla Laravel Backup Script
# Dumps MySQL database and archives storage directory
# Run via host cron: 0 3 * * * /path/to/deploy/backup.sh
# ============================================================

# --- Configuration ---
BACKUP_DIR="${BACKUP_DIR:-/var/backups/polla}"
DB_CONTAINER="${DB_CONTAINER:-polla-db}"
DB_DATABASE="${DB_DATABASE:-polla}"
DB_USERNAME="${DB_USERNAME:-polla}"
DB_PASSWORD="${DB_PASSWORD:-}"
STORAGE_PATH="${STORAGE_PATH:-/var/www/polla/storage}"
PROJECT_ROOT="${PROJECT_ROOT:-/var/www/polla}"
RETENTION_DAYS="${RETENTION_DAYS:-7}"

# --- Colors ---
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

info()  { echo -e "${GREEN}[INFO]${NC} $1"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $1"; }
error() { echo -e "${RED}[ERROR]${NC} $1"; }

# --- Prerequisites ---
if ! command -v docker &> /dev/null; then
    error "docker is not installed."
    exit 1
fi

mkdir -p "$BACKUP_DIR"

# --- Timestamp ---
TIMESTAMP=$(date +'%Y-%m-%d_%H-%M-%S')
BACKUP_PATH="${BACKUP_DIR}/${TIMESTAMP}"
mkdir -p "$BACKUP_PATH"

info "Starting backup: ${TIMESTAMP}"

# --- 1. MySQL Dump ---
info "Dumping MySQL database..."
cd "$PROJECT_ROOT"

if [ -n "$DB_PASSWORD" ]; then
    docker compose exec -T "$DB_CONTAINER" \
        mysqldump \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        -u "$DB_USERNAME" \
        -p"$DB_PASSWORD" \
        "$DB_DATABASE" \
        | gzip > "${BACKUP_PATH}/database.sql.gz"
else
    docker compose exec -T "$DB_CONTAINER" \
        mysqldump \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        -u "$DB_USERNAME" \
        "$DB_DATABASE" \
        | gzip > "${BACKUP_PATH}/database.sql.gz"
fi

info "Database dump completed."

# --- 2. Storage Backup ---
info "Archiving storage directory..."
tar -czf "${BACKUP_PATH}/storage-backup.tar.gz" \
    -C "$(dirname "$STORAGE_PATH")" \
    "$(basename "$STORAGE_PATH")" 2>/dev/null || \
    warn "Storage directory not found at ${STORAGE_PATH}; skipping."

info "Storage archive completed."

# --- 3. Cleanup old backups ---
info "Removing backups older than ${RETENTION_DAYS} days..."
find "$BACKUP_DIR" -mindepth 1 -maxdepth 1 -type d -mtime "+${RETENTION_DAYS}" -exec rm -rf {} \;
info "Cleanup completed."

# --- 4. Summary ---
DB_SIZE=$(du -sh "${BACKUP_PATH}/database.sql.gz" 2>/dev/null | cut -f1 || echo "N/A")
STORAGE_SIZE=$(du -sh "${BACKUP_PATH}/storage-backup.tar.gz" 2>/dev/null | cut -f1 || echo "N/A")

info "Backup completed successfully!"
echo ""
echo "  Location: ${BACKUP_PATH}"
echo "  Database: ${DB_SIZE}"
echo "  Storage:  ${STORAGE_SIZE}"
echo "  Total:    $(du -sh "$BACKUP_PATH" | cut -f1)"
