# 🚀 Hướng dẫn Deploy M4V.ME

## 📋 Yêu cầu hệ thống

### Production Server
- **OS**: Ubuntu 20.04+ / CentOS 8+ / Windows Server 2019+
- **PHP**: >= 8.1 với extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML, ZIP
- **Composer**: Latest version
- **Node.js**: >= 16.x & NPM
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.2
- **Web Server**: Apache 2.4+ hoặc Nginx 1.18+
- **SSL Certificate**: Let's Encrypt hoặc commercial certificate

### Development Environment
- **Local**: XAMPP, WAMP, Laragon (Windows) hoặc MAMP (Mac)
- **Docker**: Docker Desktop với docker-compose
- **IDE**: VS Code, PhpStorm, hoặc Sublime Text

## 🖥️ Deploy trên Linux Server (Ubuntu/CentOS)

### 1. Cài đặt hệ thống
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-mbstring php8.1-gd php8.1-bcmath

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs

# Install MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

# Install Nginx
sudo apt install nginx
```

### 2. Clone và setup project
```bash
# Clone repository
git clone https://github.com/your-username/m4v-clone.git
cd m4v-clone

# Make scripts executable
chmod +x setup.sh deploy.sh

# Run first-time setup
./setup.sh
```

### 3. Cấu hình Nginx
```bash
# Create Nginx config
sudo nano /etc/nginx/sites-available/m4v-clone

# Enable site
sudo ln -s /etc/nginx/sites-available/m4v-clone /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 4. Cấu hình SSL (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🪟 Deploy trên Windows Server

### 1. Cài đặt XAMPP hoặc Laragon
- Download từ https://www.apachefriends.org/ hoặc https://laragon.org/
- Cài đặt với PHP 8.1+, MySQL, Apache

### 2. Setup project
```cmd
# Clone repository
git clone https://github.com/your-username/m4v-clone.git
cd m4v-clone

# Install dependencies
composer install
npm install

# Copy environment file
copy .env.example .env

# Generate key
php artisan key:generate

# Build assets
npm run build
```

### 3. Cấu hình database
```sql
-- Tạo database trong phpMyAdmin hoặc MySQL command line
CREATE DATABASE m4v_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tạo user
CREATE USER 'm4v_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON m4v_production.* TO 'm4v_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Chạy migrations
```cmd
# Run migrations
php artisan migrate --force

# Run seeders
php artisan db:seed --force

# Create storage link
php artisan storage:link
```

## 🐳 Deploy với Docker

### 1. Tạo docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: m4v-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - m4v-network
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    container_name: m4v-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - m4v-network

  mysql:
    image: mysql:8.0
    container_name: m4v-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: m4v_production
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: m4v_user
      MYSQL_PASSWORD: user_password
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - m4v-network

  redis:
    image: redis:alpine
    container_name: m4v-redis
    restart: unless-stopped
    networks:
      - m4v-network

volumes:
  mysql_data:
    driver: local

networks:
  m4v-network:
    driver: bridge
```

### 2. Tạo Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

### 3. Deploy với Docker
```bash
# Build and start containers
docker-compose up -d --build

# Install dependencies
docker-compose exec app composer install --optimize-autoloader --no-dev
docker-compose exec app npm install
docker-compose exec app npm run build

# Run migrations
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force

# Create storage link
docker-compose exec app php artisan storage:link
```

## 🔧 Cấu hình Production

### 1. Environment Variables (.env)
```env
APP_NAME="M4V.ME"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=m4v_production
DB_USERNAME=m4v_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

### 2. Web Server Configuration

#### Nginx
```nginx
server {
    listen 80;
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/m4v-clone/public;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/private.key;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    charset utf-8;
    
    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 🚀 Automated Deployment

### 1. Deploy Script (Linux/Mac)
```bash
# Make executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

### 2. Windows Batch Script
```batch
@echo off
echo Deploying M4V.ME...

git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

echo Deployment completed!
```

### 3. CI/CD với GitHub Actions
```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Deploy to server
      uses: appleboy/ssh-action@v0.1.5
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /path/to/m4v-clone
          ./deploy.sh
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Permission Errors
```bash
# Fix file permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 2. Database Connection Issues
```bash
# Check MySQL service
sudo systemctl status mysql
sudo systemctl restart mysql

# Test connection
php artisan tinker
DB::connection()->getPdo();
```

#### 3. Storage Link Issues
```bash
# Remove and recreate storage link
rm public/storage
php artisan storage:link
```

#### 4. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Performance Optimization

#### 1. Database Indexes
```sql
-- Add performance indexes
ALTER TABLE guild_posts ADD INDEX idx_created_at (created_at);
ALTER TABLE guild_post_comments ADD INDEX idx_post_id (post_id);
ALTER TABLE guild_post_likes ADD INDEX idx_post_id (post_id);
ALTER TABLE notifications ADD INDEX idx_user_read (user_id, read_at);
```

#### 2. PHP Configuration
```ini
; php.ini optimizations
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
opcache.validate_timestamps = 0
```

#### 3. Nginx Optimizations
```nginx
# Enable gzip compression
gzip on;
gzip_types text/css application/javascript application/json;

# Cache static files
location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## 📊 Monitoring & Maintenance

### 1. Log Monitoring
```bash
# View application logs
tail -f storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# View MySQL logs
tail -f /var/log/mysql/error.log
```

### 2. Performance Monitoring
```bash
# Check Laravel performance
php artisan about

# Monitor database queries
php artisan tinker
DB::enableQueryLog();
# Run some queries
DB::getQueryLog();
```

### 3. Backup Strategy
```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public

# Automated backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p database_name > /backups/db_$DATE.sql
tar -czf /backups/files_$DATE.tar.gz /path/to/storage/app/public
find /backups -name "*.sql" -mtime +7 -delete
find /backups -name "*.tar.gz" -mtime +7 -delete
```

## 📞 Support

Nếu gặp vấn đề trong quá trình deploy, hãy:

1. **Kiểm tra logs**: `tail -f storage/logs/laravel.log`
2. **Kiểm tra permissions**: File và folder phải có quyền đúng
3. **Kiểm tra database**: Connection và migrations
4. **Kiểm tra web server**: Configuration và service status
5. **Tạo issue**: Trên GitHub repository

---

**Happy Deploying! 🚀**
