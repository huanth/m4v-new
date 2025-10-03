# M4V.ME - Cộng đồng đích thực

Một nền tảng cộng đồng trực tuyến được xây dựng với Laravel, cung cấp các tính năng tương tác xã hội như bang hội, tin nhắn, thông báo và quản lý người dùng.

## 🚀 Tính năng chính

### 👥 Quản lý người dùng
- **Đăng ký/Đăng nhập** với username và email
- **Hệ thống phân quyền** (Super Admin, Admin, SMod, FMod, User)
- **Quản lý avatar** người dùng
- **Hệ thống ban** với lý do, thời gian và loại ban

### 🏰 Bang hội (Guilds)
- **Tạo và quản lý bang hội** (chỉ Super Admin và Admin)
- **Hệ thống thành viên** với các vai trò: Bang chủ, Phó bang, Trưởng lão, Thành viên
- **Quản lý danh mục** bài viết trong bang hội
- **Banner và thông báo** bang hội
- **Truy cập công khai** - ai cũng có thể xem bang hội

### 📝 Bài viết và Bình luận
- **Tạo bài viết** trong bang hội với danh mục
- **Bình luận** và **thích** bài viết/bình luận
- **Ghim và khóa** bài viết (dành cho quản trị viên)
- **Đếm lượt xem** bài viết

### 💬 Tin nhắn
- **Tin nhắn real-time** giữa các người dùng
- **Hiển thị số cuộc trò chuyện** chưa đọc
- **Giao diện chat** thân thiện

### 🔔 Thông báo
- **Thông báo tự động** khi có người thích/bình luận
- **Đánh dấu đã đọc** thông báo
- **Hiển thị số thông báo** chưa đọc trong header

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates + Tailwind CSS
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

### 1. Clone repository
```bash
git clone <repository-url>
cd m4v-clone
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình database
Chỉnh sửa file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=m4v_clone
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Chạy migrations
```bash
php artisan migrate
php artisan db:seed
```

### 6. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 7. Build assets
```bash
npm run build
# hoặc cho development
npm run dev
```

### 8. Chạy server
```bash
php artisan serve
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

## 👥 Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với:
- **Super Admin**: `admin` / `password`
- **Admin**: `admin2` / `password`
- **User thường**: `user` / `password`

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

## 🎯 API Endpoints

### Authentication
- `POST /login` - Đăng nhập
- `POST /register` - Đăng ký
- `POST /logout` - Đăng xuất

### Guilds
- `GET /guilds` - Danh sách bang hội
- `GET /{id}` - Xem bang hội
- `POST /{id}/join` - Tham gia bang hội
- `POST /{id}/leave` - Rời bang hội

### Notifications
- `GET /notifications` - Danh sách thông báo
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

## 📞 Liên hệ

- **Email**: support@m4v.me
- **Website**: https://m4v.me

---

**M4V.ME** - Nơi kết nối cộng đồng đích thực! 🎉