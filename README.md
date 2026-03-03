# M4V.ME - Cộng Đồng Đích Thực

Một nền tảng cộng đồng trực tuyến được xây dựng với **Laravel**, hỗ trợ các tiện ích tương tác chuyên sâu như hệ thống Bang hội, Nhắn tin theo thời gian thực (Real-time), Thông báo đẩy và Quản trị người dùng toàn diện.

## 🚀 Tính Năng Nổi Bật

### 👥 Quản Lý Người Dùng
- **Đăng ký/Đăng nhập**: Hỗ trợ tài khoản và mật khẩu, xác thực qua email (tuỳ chọn).
- **Hệ thống phân quyền (Roles)**: Cung cấp đầy đủ các cấp bậc từ Super Admin, Admin, SMod, FMod cho đến User bình thường.
- **Tùy chỉnh Hồ sơ (Profile)**: Quản lý ảnh đại diện, xem nhật ký hoạt động, số liệu thống kê cá nhân `/user/{id}`.
- **Kiểm soát vi phạm (Ban system)**: Cấm tài khoản có thời hạn/vô thời hạn kèm lý do cụ thể.

### 🏠 Trang Chủ Thông Minh
- **Nguồn cấp dữ liệu (Feed)**: Tổng hợp bài viết mới nhất từ tất cả các bang hội.
- **Thuật toán sắp xếp**: Tự động đưa các chủ đề nóng và mới lên đầu tiên.
- **Bảng xếp hạng**: Hiển thị danh sách Bang hội có quy mô lớn nhất.
- **Phản hồi (Responsive)**: Giao diện thích ứng tốt trên cả máy tính lẫn điện thoại.

### 🏰 Hệ Thống Bang Hội (Guilds)
- **Kiểm soát & Quản lý**: Quyền khởi tạo bang hội nằm trong tay Quản trị viên (Super Admin / Admin).
- **Phân cấp Tổ chức**: Bao gồm Bang chủ, Phó bang, Trưởng lão, Thành viên.
- **Khu vực thảo luận**: Chia nhỏ bang hội thành nhiều danh mục để dễ quản lý.
- **Truyền thông nội bộ**: Cài đặt ảnh bìa và ghim thông báo toàn bang.
- **Tính mở**: Khách vãng lai cũng có thể dạo xem các bài viết công khai.

### 📝 Thảo Luận & Tương Tác
- **Trình soạn thảo văn bản phong phú (Rich Text)**: Tích hợp TinyMCE 7 cho trải nghiệm viết hoàn hảo.
- **Cơ chế bình luận**: Bình luận tuyến tính (phẳng), hỗ trợ tính năng trích dẫn nội dung (Quote).
- **Thể hiện cảm xúc**: Thích bài viết hoặc bình luận.
- **Công cụ điều phối**: Quản trị viên có thể ghim (Pin) và khóa (Lock) các bài viết sai phạm.
- **Theo dõi chỉ số**: Đếm lượt xem, phân trang nội dung (10 phản hồi/trang).

### 💬 Trò Chuyện Trực Tuyến
- **Tin nhắn thời gian thực**: Sử dụng WebSockets đem lại tốc độ nhắn tin tức thì.
- **Quản lý cuộc gọi**: Đếm số tin nhắn chưa đọc, hiển thị danh sách thân thiện.

### 🔔 Thông Báo Đẩy (Notifications)
- **Tự động hóa**: Bạn sẽ nhận được thông báo ngay khi có ai đó thích/trả lời bình luận của bạn.
- **Điều hướng thông minh**: Click vào thông báo sẽ tự động xử lý trạng thái "Đã đọc" và đưa người dùng đến đúng bài viết đó.

---

## 🛠️ Công Nghệ Chức Năng

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Trình soạn thảo**: TinyMCE 7 (CDN)
- **Hệ cơ sở dữ liệu**: MySQL / MariaDB
- **Xử lý thời gian thực**: Pusher
- **Lưu trữ tĩnh (Storage)**: Laravel Storage
- **Định danh (Auth)**: Laravel Session/Auth

---

## 📋 Yêu Cầu Môi Trường (System Requirements)

- **PHP**: >= 8.1
- **Composer**: Phiên bản mới nhất
- **Node.js**: >= 16.x & NPM
- **Database**: MySQL >= 5.7 hoặc MariaDB >= 10.2
- **Web Server**: NGINX hoặc Apache

---

## 🚀 Hướng Dẫn Cài Đặt (Server / Production)

### 1. Tải Mã Nguồn
```bash
git clone https://github.com/huanth/m4v-new.git
cd m4v-new
```

### 2. Cài Đặt Thư Viện
```bash
# Cài đặt PHP dependencies
composer install --optimize-autoloader --no-dev

# Cài đặt Javascript/CSS dependencies
npm install

# Biên dịch assets tĩnh
npm run build
```

### 3. Cấu Hình Biến Môi Trường
```bash
cp .env.example .env

# Sinh khóa bảo mật mới
php artisan key:generate

# Mở file .env để sửa các thông số kết nối Database
nano .env
```

*Trong file `.env`, vui lòng cập nhật:*
```env
APP_NAME="M4V.ME"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=m4v_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

### 4. Thiết Lập Khởi Tạo (Database Migrations)
```bash
# Chạy tệp lệnh DB (Migrations)
php artisan migrate --force

# Đổ dữ liệu hạt giống (Seed) để có tài khoản admin mặc định.
php artisan db:seed --force
```

### 5. Cấp Quyền Truy Cập (Permissions & Storage)
```bash
# Tạo liên kết tượng trưng (Symlink) cho Storage
php artisan storage:link

# Trao quyền sở hữu cho Web Server (VD: www-data của Nginx/Apache)
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chmod -R 755 public
```

### 6. Tối Ưu Tốc Độ Xử Lý
```bash
php artisan config:cache
php artisan view:cache
# Nếu source-code không dùng Closure cho Route thì có thể chạy lệnh dưới
# php artisan route:cache

composer dump-autoload --optimize
```

---

## 🔧 Hướng Dẫn Chạy Môi Trường Cục Bộ (Local Development)

```bash
# Lấy code và truy cập thư mục
git clone https://github.com/huanth/m4v-new.git
cd m4v-new

# Cài packages
composer install
npm install

# Khởi tạo db, .env
cp .env.example .env
php artisan key:generate

# Dựng CSDL
php artisan migrate
php artisan db:seed
php artisan storage:link

# Khởi chạy server
php artisan serve

# Build hoặc theo dõi sự thay đổi CSS/JS trên terminal thứ 2
npm run dev
```

---

## 🔄 Cấu Hình Deployment Tự Động
Hệ thống có cung cấp sẵn một Script `deploy.sh` theo chuẩn quy trình tự động.
Mỗi khi có code mới trên Git, bạn có thể thực thi để update Server:
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🗂️ Cấu Trúc Dự Án (Project Structure)
Với mục tiêu mở rộng dài hạn, dự án sử dụng kiến trúc Service Layer/Repository để Clean Code:
- **`app/Http/Controllers/`**: Điều phối luồng và trả kết quả HTTP (Đã được làm mỏng, chuyên biệt hoá).
- **`app/Services/`**: Nơi chứa toàn bộ cốt lõi Logic và Thuật toán chức năng chuyên sâu (VD: `GuildService`, `GuildMemberService`).
- **`app/Policies/`**: Kiểm soát phân quyền tinh vi, ngăn chặn các hành vi vi phạm an ninh hệ thống.
- **`resources/views/`**: Thiết kế giao diện (Blade Template).

---

## 🎯 Tối Ưu Hiệu Năng (Performance Optimization)
- **Eager Loading**: Tránh lỗi N+1 Queries khi trích xuất danh sách bài viết/bình luận ra màn hình.
- **Gộp Index Data**: Sử dụng Index trong CSDL cho các bảng nặng (Ví dụ: Likes, Comments) để lướt Web với độ trễ thấp nhất.
- **Bộ Nhớ Đệm**: Ứng dụng Redis Cache hoặc Opcache tăng tốc PHP.

---

## 🛡️ Best Practices về Bảo Mật

1. Không bao giờ vô tình đưa file `.env` lên Github.
2. Vận hành qua HTTPS giao tiếp SSL/TLS là yêu cầu bắt buộc khi mở cổng mạng trên máy chủ Internet.
3. Hạn chế việc phân quyền tài khoản (Chỉ nên gán Role Admin/Super Admin cho nhóm nhân sự quan trọng).
4. Xác định và rà soát mọi truy vấn đầu vào thông qua **Laravel Form Requests** / **Validator**.

---

## 📞 Hỗ Trợ và Khắc Phục Sự Cố

- Nếu bạn gặp lỗi như: `Permission denied`, hãy chắc chắn bạn đã chạy lại khối lệnh trao quyền sở hữu (chown) cho thư mục `storage/`.
- Nếu lỗi trang xuất hiện màn hình trắng hoặc HTTP 500, xem chi tiết nguyên nhân ở `/storage/logs/laravel.log`.
- Lỗi hình ảnh không hiển thị: Bạn có thể quên thực thi lệnh `php artisan storage:link` để hệ thống đem thư mục chứa ảnh từ `/storage/app/public` ra truy cập diện rộng `/public/storage`.

**M4V.ME** - Nơi kết nối cộng đồng đích thực! 🎉
*Dự án phát triển sử dụng nền tảng mở Laravel Framework với luồng kiến trúc cao cấp.*