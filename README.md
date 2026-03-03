# M4V.ME - Cộng đồng đích thực

Một nền tảng cộng đồng trực tuyến được xây dựng với Laravel, cung cấp các tính năng tương tác xã hội như bang hội, tin nhắn, thông báo và quản lý người dùng.

## 🚀 Tính năng chính

### 👥 Quản lý người dùng
- **Đăng ký/Đăng nhập** với username và email
- **Hệ thống phân quyền** (Super Admin, Admin, SMod, FMod, User)
- **Quản lý avatar** người dùng
- **Hệ thống ban** với lý do, thời gian và loại ban
- **Profile công khai** tại `/user/{id}`

### 🏠 Trang chủ thông minh
- **Hiển thị bài viết mới nhất** từ tất cả bang hội
- **Sắp xếp thông minh**: Ưu tiên bài viết mới và có tương tác
- **Bang hội nổi bật** theo số lượng thành viên
- **Responsive design** cho mọi thiết bị

### 🏰 Bang hội (Guilds)
- **Tạo và quản lý bang hội** (chỉ Super Admin và Admin)
- **Hệ thống thành viên** với các vai trò: Bang chủ, Phó bang, Trưởng lão, Thành viên
- **Quản lý danh mục** bài viết trong bang hội
- **Banner và thông báo** bang hội
- **Truy cập công khai** - ai cũng có thể xem bang hội
- **Super Admin & Admin** có toàn quyền quản lý tất cả bang hội

### 📝 Bài viết và Bình luận
- **Tạo bài viết** trong bang hội với danh mục
- **Rich Text Editor (TinyMCE)** cho soạn thảo bài viết và bình luận
- **Bình luận phẳng** - tất cả comment ở cùng cấp
- **Trích dẫn comment** khi trả lời
- **Thích** bài viết/bình luận
- **Ghim và khóa** bài viết (dành cho quản trị viên)
- **Đếm lượt xem** bài viết
- **Phân trang comment** (10 comment/trang)
- **Sắp xếp thông minh**: Ưu tiên bài viết mới và có tương tác

### 💬 Tin nhắn
- **Tin nhắn real-time** giữa các người dùng
- **Hiển thị số cuộc trò chuyện** chưa đọc
- **Giao diện chat** thân thiện

### 🔔 Thông báo thông minh
- **Thông báo tự động** khi có người thích/bình luận
- **Click thông báo → tự động đánh dấu đã đọc + redirect** đến bài viết/comment
- **Đánh dấu đã đọc** thủ công từng thông báo hoặc tất cả
- **Hiển thị số thông báo** chưa đọc trong header
- **Thông báo trả lời comment** cho tác giả comment gốc

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Rich Text Editor**: TinyMCE 7 (CDN)
- **Database**: MySQL
- **Real-time**: Pusher (cho tin nhắn)
- **File Storage**: Laravel Storage
- **Authentication**: Laravel Auth

## 📋 Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM
- Laravel Sail (tùy chọn)

## 🚀 Cài đặt

### 📋 Yêu cầu hệ thống
- **PHP**: >= 8.1
- **Composer**: Latest version
- **Node.js**: >= 16.x & NPM
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.2
- **Web Server**: Apache/Nginx
- **SSL Certificate**: (Khuyến nghị cho production)

### 🖥️ Cài đặt trên Server (Production)

#### 1. Clone repository
```bash
git clone https://github.com/your-username/m4v-clone.git
cd m4v-clone
```

#### 2. Cài đặt dependencies
```bash
# Cài đặt PHP dependencies
composer install --optimize-autoloader --no-dev

# Cài đặt Node.js dependencies
npm install

# Build assets cho production
npm run build
```

#### 3. Cấu hình môi trường
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Chỉnh sửa file .env cho production
nano .env
```

#### 4. Cấu hình .env cho Production
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
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis (Khuyến nghị cho production)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Pusher (Real-time)
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster

# TinyMCE Rich Text Editor
# Lấy API key miễn phí tại: https://www.tiny.cloud/my-account/integrate/
TINYMCE_API_KEY=your-tinymce-api-key
VITE_TINYMCE_API_KEY=your-tinymce-api-key

# File Storage
FILESYSTEM_DISK=local
```

#### 5. Tạo database và user
```sql
-- Đăng nhập MySQL
mysql -u root -p

-- Tạo database
CREATE DATABASE m4v_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tạo user
CREATE USER 'm4v_user'@'localhost' IDENTIFIED BY 'strong_password_here';

-- Cấp quyền
GRANT ALL PRIVILEGES ON m4v_production.* TO 'm4v_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 6. Chạy migrations và seeder
```bash
# Chạy migrations
php artisan migrate --force

# Chạy seeder (tạo tài khoản admin mặc định)
php artisan db:seed --force
```

#### 7. Cấu hình storage và permissions
```bash
# Tạo symbolic link cho storage
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public
```

#### 8. Tối ưu hóa cho Production
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 🌐 Cấu hình Web Server

#### Nginx Configuration
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
    
    # Security headers
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
}
```

#### Apache Configuration (.htaccess)
```apache
# File: public/.htaccess
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

### 🔧 Cài đặt trên Local Development

#### 1. Clone và setup
```bash
git clone https://github.com/your-username/m4v-clone.git
cd m4v-clone
```

#### 2. Cài đặt dependencies
```bash
composer install
npm install
```

#### 3. Cấu hình environment
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Cấu hình .env cho Development
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database local
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=m4v_local
DB_USERNAME=root
DB_PASSWORD=

# Queue sync cho development
QUEUE_CONNECTION=sync
```

#### 5. Setup database
```bash
# Tạo database local
mysql -u root -p
CREATE DATABASE m4v_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Chạy migrations
php artisan migrate
php artisan db:seed

# Tạo storage link
php artisan storage:link
```

#### 6. Chạy development server
```bash
# Build assets
npm run dev

# Chạy Laravel server
php artisan serve

# Hoặc chạy Vite dev server
npm run dev
```

### 🐳 Docker Deployment (Tùy chọn)

#### docker-compose.yml
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

### 🔄 Scripts tự động hóa

#### deploy.sh (Production)
```bash
#!/bin/bash

echo "🚀 Deploying M4V.ME..."

# Pull latest code
git pull origin main

# Install/Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "✅ Deployment completed!"
```

#### setup.sh (First time setup)
```bash
#!/bin/bash

echo "🔧 Setting up M4V.ME for the first time..."

# Copy environment file
cp .env.example .env

# Install dependencies
composer install
npm install

# Generate key
php artisan key:generate

# Build assets
npm run build

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "✅ Setup completed!"
echo "🌐 Visit your site at: http://your-domain.com"
echo "👤 Login with: admin / password"
```

## 🔧 Cấu hình bổ sung

### SMTP (Email)
Cấu hình trong `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Pusher (Real-time messaging)
Cấu hình trong `.env`:
```env
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

## 👥 Tài khoản

- **Super Admin tự động**: Tài khoản **đầu tiên** được đăng ký sẽ tự động nhận role `SADMIN`
- Các tài khoản tiếp theo mặc định role `user`

## 🔄 Changelog

### Version 3.0 (Latest)
- ✏️ **TinyMCE Rich Text Editor** tích hợp vào bài viết và bình luận
- 🔔 **Fix notification redirect**: Click thông báo → mark-as-read + redirect đúng URL
- 👑 **Auto SADMIN**: Tài khoản đầu tiên đăng ký nhận quyền Super Admin
- 🐛 **Fix related_type**: Chuẩn hóa FQN cho morphTo notification

### Version 2.0
- ✨ **Trang chủ thông minh** với bài viết mới nhất và bang hội nổi bật
- 🧠 **Thuật toán sắp xếp thông minh** ưu tiên bài viết mới và có tương tác
- 💬 **Bình luận phẳng** với trích dẫn và phân trang
- 🔗 **Thông báo có link** trực tiếp đến nội dung
- 📱 **Responsive design** hoàn toàn cho mobile và desktop
- 🔒 **Phân quyền nâng cao** cho Super Admin và Admin
- ⚡ **Performance optimization** với subquery và eager loading

### Version 1.0
- 🏰 Hệ thống bang hội cơ bản
- 👥 Quản lý người dùng và phân quyền
- 📝 Bài viết và bình luận
- 💬 Tin nhắn real-time
- 🔔 Hệ thống thông báo

## 📁 Cấu trúc dự án

```
app/
├── Http/Controllers/          # Controllers
│   ├── Auth/                 # Authentication controllers
│   ├── Admin/                # Admin controllers
│   └── ...
├── Models/                   # Eloquent models
├── Services/                 # Business logic services
└── ...

database/
├── migrations/               # Database migrations
├── seeders/                  # Database seeders
└── factories/                # Model factories

resources/
├── views/                    # Blade templates
│   ├── components/           # Reusable components
│   ├── guilds/              # Guild-related views
│   ├── notifications/       # Notification views
│   └── ...
├── css/                     # Stylesheets
└── js/                      # JavaScript files

routes/
└── web.php                  # Web routes
```

## 🎯 Tính năng nâng cao

### 🧠 Thuật toán sắp xếp thông minh
- **Priority 1**: Bài viết có tương tác + mới (trong 24h) = **Hot Posts**
- **Priority 2**: Bài viết siêu mới (trong 2h) = **Fresh Posts**  
- **Priority 3**: Bài viết có tương tác = **Active Posts**
- **Priority 4**: Bài viết khác = **Other Posts**

### 🔒 Hệ thống phân quyền
- **Super Admin**: Toàn quyền hệ thống + quản lý tất cả bang hội
- **Admin**: Quản lý bang hội + quyền xóa/sửa comment
- **Guild Roles**: Leader, Vice Leader, Elder có quyền quản lý bang hội
- **Member**: Tham gia bang hội, tạo bài viết, bình luận

### 📱 Responsive Design
- **Mobile-first** approach với Tailwind CSS
- **Header responsive** tự động điều chỉnh theo màn hình
- **Comment section** tối ưu cho mobile và desktop
- **Grid layout** linh hoạt cho các thiết bị khác nhau

### 🚀 Performance Optimization
- **Eager loading** cho relationships
- **Subquery optimization** cho việc đếm likes/comments
- **Cache management** cho hoạt động real-time
- **Pagination** cho comments (10/trang)

## 🎯 API Endpoints

### Authentication
- `POST /login` - Đăng nhập
- `POST /register` - Đăng ký  
- `POST /logout` - Đăng xuất

### Home
- `GET /` - Trang chủ với bài viết mới nhất

### Guilds
- `GET /guilds` - Danh sách bang hội
- `GET /guilds/{id}` - Xem bang hội
- `GET /guilds/{id}/posts/{postId}` - Xem bài viết chi tiết
- `POST /guilds/{id}/join` - Tham gia bang hội
- `POST /guilds/{id}/leave` - Rời bang hội
- `POST /guilds/{id}/posts` - Tạo bài viết mới

### Comments & Interactions
- `POST /guilds/{id}/posts/{postId}/comments` - Thêm bình luận
- `POST /guilds/{id}/posts/{postId}/like` - Thích/bỏ thích bài viết
- `POST /guilds/{id}/posts/{postId}/comments/{commentId}/like` - Thích/bỏ thích comment

### User Profile
- `GET /user/{id}` - Xem profile công khai
- `GET /user/profile` - Profile cá nhân
- `GET /user/{user}/admin/ban-history` - Lịch sử ban (Admin only)

### Notifications
- `GET /notifications` - Danh sách thông báo
- `GET /notifications/{id}/redirect` - Click thông báo: mark-as-read + redirect
- `POST /notifications/{id}/mark-read` - Đánh dấu đã đọc
- `POST /notifications/mark-all-read` - Đánh dấu tất cả đã đọc

## 🤝 Đóng góp

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Mở Pull Request

## 📄 License

Dự án này được phân phối dưới MIT License. Xem file `LICENSE` để biết thêm chi tiết.

## 🔧 Troubleshooting

### ❌ Lỗi thường gặp

#### 1. Permission denied errors
```bash
# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public
```

#### 2. Database connection errors
```bash
# Check database service
sudo systemctl status mysql

# Test connection
php artisan tinker
DB::connection()->getPdo();
```

#### 3. Storage link errors
```bash
# Remove existing link and recreate
rm public/storage
php artisan storage:link
```

#### 4. Cache issues
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

#### 5. Composer/Node issues
```bash
# Clear composer cache
composer clear-cache
composer install --no-cache

# Clear npm cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

### 🔍 Debug Commands

```bash
# Check Laravel configuration
php artisan config:show

# Check database migrations status
php artisan migrate:status

# Check routes
php artisan route:list

# Check queue status
php artisan queue:work --verbose

# Check storage link
ls -la public/storage
```

### 📊 Performance Monitoring

```bash
# Check application performance
php artisan about

# Monitor database queries
# Add DB::enableQueryLog() in code
# Use DB::getQueryLog() to see queries

# Check memory usage
php -i | grep memory_limit
```

## 🛡️ Security Best Practices

### 🔒 Server Security
- **SSL Certificate**: Luôn sử dụng HTTPS
- **Firewall**: Chỉ mở ports cần thiết (80, 443, 22)
- **Regular Updates**: Cập nhật PHP, MySQL, server OS
- **Backup**: Tự động backup database và files

### 🔐 Application Security
- **Environment Variables**: Không commit .env file
- **Strong Passwords**: Sử dụng mật khẩu mạnh cho database
- **Input Validation**: Validate tất cả user input
- **CSRF Protection**: Laravel tự động enable
- **SQL Injection**: Sử dụng Eloquent ORM

### 📁 File Permissions
```bash
# Secure file permissions
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 775 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
find bootstrap/cache -type d -exec chmod 775 {} \;
```

## 🚀 Production Checklist

### ✅ Pre-deployment
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Setup SSL certificate
- [ ] Configure email settings
- [ ] Setup Redis/Memcached
- [ ] Configure file storage
- [ ] Setup monitoring/logging

### ✅ Post-deployment
- [ ] Test all major features
- [ ] Check database connections
- [ ] Verify email functionality
- [ ] Test file uploads
- [ ] Check real-time features
- [ ] Monitor performance
- [ ] Setup automated backups
- [ ] Configure error monitoring

### 📈 Performance Optimization

#### Database
```sql
-- Add indexes for better performance
ALTER TABLE guild_posts ADD INDEX idx_created_at (created_at);
ALTER TABLE guild_post_comments ADD INDEX idx_post_id (post_id);
ALTER TABLE guild_post_likes ADD INDEX idx_post_id (post_id);
```

#### PHP Configuration
```ini
; php.ini optimizations
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
opcache.enable = 1
opcache.memory_consumption = 128
```

#### Web Server
```nginx
# Nginx optimizations
gzip on;
gzip_types text/css application/javascript application/json;

# Cache static files
location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## 📞 Support & Community

### 🆘 Getting Help
- **Documentation**: Check this README first
- **Issues**: Create GitHub issue for bugs
- **Discussions**: Use GitHub Discussions for questions

### 🤝 Contributing
1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

### 📧 Contact
- **Email**: nauhyuh@gmail.com
- **GitHub**: https://github.com/huanth/m4v-new

---

**M4V.ME** - Nơi kết nối cộng đồng đích thực! 🎉

*Built with ❤️ using Laravel & Tailwind CSS*