#!/bin/bash

# M4V.ME Production Deployment Script
# Usage: ./deploy.sh

set -e  # Exit on any error

echo "🚀 Deploying M4V.ME to Production..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "Not in Laravel project directory. Please run from project root."
    exit 1
fi

# Check if .env exists
if [ ! -f ".env" ]; then
    print_error ".env file not found. Please run setup.sh first."
    exit 1
fi

# Create backup before deployment
print_status "Creating backup..."
BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

if [ -d "storage/app/public" ]; then
    cp -r storage/app/public "$BACKUP_DIR/"
    print_success "Files backed up to $BACKUP_DIR"
fi

# Pull latest code
print_status "Pulling latest code from repository..."
git pull origin main

# Install/Update dependencies
print_status "Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

print_status "Installing Node.js dependencies..."
npm ci

print_status "Building assets for production..."
npm run build

# Run migrations
print_status "Running database migrations..."
php artisan migrate --force

# Clear and rebuild caches
print_status "Clearing old caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

print_status "Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
print_status "Optimizing autoloader..."
composer dump-autoload --optimize

# Set permissions
print_status "Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public

# Check if storage link exists
if [ ! -L "public/storage" ]; then
    print_status "Creating storage link..."
    php artisan storage:link
fi

# Restart queue workers (if using queues)
print_status "Restarting queue workers..."
sudo supervisorctl restart m4v-worker:* || print_warning "Queue workers not configured"

# Clear OPcache (if available)
if command -v php &> /dev/null; then
    print_status "Clearing OPcache..."
    php -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'OPcache cleared'; } else { echo 'OPcache not available'; }"
fi

# Health check
print_status "Performing health check..."
if php artisan about > /dev/null 2>&1; then
    print_success "Application is healthy"
else
    print_error "Application health check failed"
    exit 1
fi

print_success "🎉 Deployment completed successfully!"
print_status "Application URL: $(grep APP_URL .env | cut -d '=' -f2)"
print_status "Deployment time: $(date)"

echo ""
echo "📋 Post-deployment checklist:"
echo "  ✓ Test website functionality"
echo "  ✓ Check database connections"
echo "  ✓ Verify email functionality"
echo "  ✓ Test file uploads"
echo "  ✓ Monitor application logs"
echo ""
echo "🔍 Useful commands:"
echo "  • View logs: tail -f storage/logs/laravel.log"
echo "  • Check status: php artisan about"
echo "  • Clear cache: php artisan cache:clear"
echo ""
