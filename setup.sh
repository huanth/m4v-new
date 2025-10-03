#!/bin/bash

# M4V.ME First Time Setup Script
# Usage: ./setup.sh

set -e  # Exit on any error

echo "🔧 Setting up M4V.ME for the first time..."

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
if [ ! -f "composer.json" ]; then
    print_error "Not in Laravel project directory. Please run from project root."
    exit 1
fi

# Check system requirements
print_status "Checking system requirements..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
REQUIRED_PHP="8.1"

if [ "$(printf '%s\n' "$REQUIRED_PHP" "$PHP_VERSION" | sort -V | head -n1)" = "$REQUIRED_PHP" ]; then
    print_success "PHP version $PHP_VERSION is supported"
else
    print_error "PHP version $PHP_VERSION is not supported. Required: $REQUIRED_PHP or higher"
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    print_success "Composer is installed"
else
    print_error "Composer is not installed. Please install Composer first."
    exit 1
fi

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v | cut -d'v' -f2)
    print_success "Node.js version $NODE_VERSION is installed"
else
    print_error "Node.js is not installed. Please install Node.js first."
    exit 1
fi

# Check MySQL
if command -v mysql &> /dev/null; then
    print_success "MySQL is available"
else
    print_warning "MySQL command not found. Make sure MySQL is installed and running."
fi

# Copy environment file
print_status "Setting up environment configuration..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    print_success "Environment file created from .env.example"
else
    print_warning ".env file already exists, skipping..."
fi

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install --no-interaction

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
npm install

# Generate application key
print_status "Generating application key..."
php artisan key:generate

# Build assets
print_status "Building assets..."
npm run build

# Database setup
print_status "Setting up database..."

# Check if database configuration exists
DB_DATABASE=$(grep "DB_DATABASE=" .env | cut -d '=' -f2)
DB_USERNAME=$(grep "DB_USERNAME=" .env | cut -d '=' -f2)
DB_PASSWORD=$(grep "DB_PASSWORD=" .env | cut -d '=' -f2)

if [ -z "$DB_DATABASE" ] || [ "$DB_DATABASE" = "laravel" ]; then
    print_warning "Please configure your database settings in .env file before running migrations."
    print_status "Edit .env file with your database credentials:"
    echo "  DB_DATABASE=your_database_name"
    echo "  DB_USERNAME=your_database_user"
    echo "  DB_PASSWORD=your_database_password"
    echo ""
    read -p "Press Enter when you've updated the .env file..."
fi

# Test database connection
print_status "Testing database connection..."
if php artisan migrate:status > /dev/null 2>&1; then
    print_success "Database connection successful"
    
    # Run migrations
    print_status "Running database migrations..."
    php artisan migrate --force
    
    # Run seeders
    print_status "Running database seeders..."
    php artisan db:seed --force
    
    print_success "Database setup completed"
else
    print_error "Database connection failed. Please check your database configuration in .env"
    print_status "Common issues:"
    echo "  • Database doesn't exist"
    echo "  • Wrong credentials"
    echo "  • MySQL service not running"
    echo "  • Wrong host/port"
    exit 1
fi

# Create storage link
print_status "Creating storage link..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    print_success "Storage link created"
else
    print_warning "Storage link already exists"
fi

# Set permissions
print_status "Setting file permissions..."
if [ -w "storage" ] && [ -w "bootstrap/cache" ]; then
    chmod -R 775 storage bootstrap/cache
    print_success "File permissions set"
else
    print_warning "Cannot set permissions automatically. Please run manually:"
    echo "  sudo chown -R www-data:www-data storage bootstrap/cache"
    echo "  sudo chmod -R 775 storage bootstrap/cache"
fi

# Clear caches
print_status "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
print_success "Caches cleared"

# Display setup information
echo ""
print_success "🎉 Setup completed successfully!"
echo ""
echo "📋 Setup Summary:"
echo "  ✓ PHP dependencies installed"
echo "  ✓ Node.js dependencies installed"
echo "  ✓ Application key generated"
echo "  ✓ Assets built"
echo "  ✓ Database migrated and seeded"
echo "  ✓ Storage link created"
echo "  ✓ File permissions set"
echo ""

# Get application URL
APP_URL=$(grep "APP_URL=" .env | cut -d '=' -f2)
echo "🌐 Application Information:"
echo "  • URL: $APP_URL"
echo "  • Environment: $(grep APP_ENV .env | cut -d '=' -f2)"
echo "  • Debug Mode: $(grep APP_DEBUG .env | cut -d '=' -f2)"
echo ""

echo "👤 Default Admin Accounts:"
echo "  • Super Admin: admin / password"
echo "  • Admin: admin2 / password"
echo "  • User: user / password"
echo ""

echo "🚀 Next Steps:"
echo "  1. Configure your web server (Apache/Nginx)"
echo "  2. Setup SSL certificate"
echo "  3. Configure email settings in .env"
echo "  4. Setup Pusher for real-time features"
echo "  5. Run: php artisan serve (for development)"
echo ""

echo "📚 Useful Commands:"
echo "  • Start dev server: php artisan serve"
echo "  • Clear cache: php artisan cache:clear"
echo "  • View logs: tail -f storage/logs/laravel.log"
echo "  • Run tests: php artisan test"
echo ""

print_success "Happy coding! 🎉"
