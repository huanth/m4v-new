#!/bin/bash

# M4V.ME Script Triển khai (Deploy)
# Cách dùng: ./deploy.sh

set -e  # Dừng script nếu có lỗi xảy ra

echo "🚀 Bắt đầu triển khai M4V.ME lên Server..."

# Màu sắc cho output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[THÔNG TIN]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[THÀNH CÔNG]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[CẢNH BÁO]${NC} $1"
}

print_error() {
    echo -e "${RED}[LỖI]${NC} $1"
}

if [ ! -f "artisan" ]; then
    print_error "Không tìm thấy file artisan. Vui lòng chạy script ở thư mục gốc của dự án Laravel."
    exit 1
fi

if [ ! -f ".env" ]; then
    print_error "Không tìm thấy file .env. Vui lòng cấu hình môi trường (.env) trước."
    exit 1
fi

print_status "Đang tạo bản sao lưu dữ liệu tĩnh (storage/app/public)..."
BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

if [ -d "storage/app/public" ]; then
    cp -r storage/app/public "$BACKUP_DIR/"
    print_success "Đã sao lưu file thành công vào $BACKUP_DIR"
fi

print_status "Đang kéo mã nguồn mới nhất từ Git..."
git pull origin main

print_status "Đang cài đặt PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

print_status "Đang cài đặt Node.js dependencies..."
npm install

print_status "Đang build frontend assets (CSS/JS)..."
npm run build

print_status "Đang chạy Database Migrations..."
php artisan migrate --force

print_status "Đang xóa bộ nhớ đệm (Cache) cũ..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

print_status "Đang thiết lập bộ nhớ đệm (Cache) mới..."
php artisan config:cache
php artisan event:cache
php artisan view:cache
# Lưu ý: Chỉ bật artisan route:cache nếu bạn không sử dụng các Router Closure.
# php artisan route:cache

print_status "Đang tối ưu hệ thống tải lớp (Autoloader)..."
composer dump-autoload --optimize

print_status "Đang thiết lập quyền truy cập tệp tin (Permissions)..."
if [ "$(id -u)" -eq 0 ]; then
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
    chmod -R 755 public
else
    print_warning "Script không chạy bằng quyền Root/Sudo. Đảm bảo user hiện tại có quyền ghi vào thư mục storage và bootstrap/cache."
fi

if [ ! -L "public/storage" ]; then
    print_status "Đang tạo Storage Link để cho phép truy cập public files..."
    php artisan storage:link
fi

print_status "Đang khởi động lại tiến trình xử lý hàng đợi (Queue Workers)..."
if command -v supervisorctl &> /dev/null && [ "$(id -u)" -eq 0 ]; then
    supervisorctl restart m4v-worker:* || print_warning "Không thấy cấu hình Queue Workers trong Supervisor."
else
    print_warning "Bỏ qua lệnh khởi động lại Queue Workers (Yêu cầu quyền sudo và gói Supervisor)."
fi

if command -v php &> /dev/null; then
    print_status "Đang dọn dẹp bộ đệm OPcache..."
    php -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'Đã xóa OPcache\n'; } else { echo 'Hệ thống OPcache không khả dụng\n'; }"
fi

print_status "Đang phân tích tình trạng hệ thống (Health Check)..."
if php artisan about > /dev/null 2>&1; then
    print_success "Hệ thống hoạt động tốt!"
else
    print_error "Health check thất bại."
    exit 1
fi

print_success "🎉 Quá trình triển khai ứng dụng M4V.ME lên máy chủ đã hoàn tất!"
print_status "Địa chỉ URL: $(grep APP_URL .env | cut -d '=' -f2)"
print_status "Thời gian: $(date)"

echo ""
echo "📋 Danh sách hậu kiểm (Post-deployment checklist):"
echo "  ✓ Truy cập giao diện để kiểm tra hoạt động."
echo "  ✓ Kiểm tra tính năng bình luận, tạo bang hội."
echo "  ✓ Kiểm tra việc upload ảnh đại diện / ảnh bìa."
echo "  ✓ Kiểm tra hệ thống nhận và gửi email."
echo "  ✓ Theo dõi xem có lỗi gì phát sinh trong file \`storage/logs/laravel.log\` không."
echo ""
echo "🔍 Một vài câu lệnh tiện ích khác:"
echo "  • Theo dõi logs trực tiếp: tail -f storage/logs/laravel.log"
echo "  • Thông tin hệ thống:     php artisan about"
echo "  • Dọn dẹp cache:          php artisan cache:clear"
echo ""
