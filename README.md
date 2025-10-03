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
- **Link trực tiếp** đến bài viết/comment được tương tác
- **Đánh dấu đã đọc** thông báo
- **Hiển thị số thông báo** chưa đọc trong header
- **Thông báo trả lời comment** cho tác giả comment gốc

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

## 🔄 Changelog

### Version 2.0 (Latest)
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