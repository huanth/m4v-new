# Cấu hình SMTP cho M4V.ME

## 1. Cấu hình file .env

Thêm các dòng sau vào file `.env`:

```env
# SMTP Configuration
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@m4v.me"
MAIL_FROM_NAME="M4V.ME - Cộng đồng đích thực"
```

## 2. Các nhà cung cấp SMTP phổ biến

### Gmail
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

**Lưu ý:** Cần tạo App Password cho Gmail:
1. Vào Google Account Settings
2. Security → 2-Step Verification (bật nếu chưa)
3. App passwords → Generate password
4. Sử dụng password này thay vì mật khẩu thường

### Outlook/Hotmail
```env
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### Yahoo
```env
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### SMTP tùy chỉnh
```env
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## 3. Test email

Sau khi cấu hình, test email bằng lệnh:

```bash
php artisan tinker
```

Trong tinker:
```php
use Illuminate\Support\Facades\Mail;
use App\Models\User;

$user = User::first();
Mail::send('emails.welcome', ['user' => $user], function ($message) use ($user) {
    $message->to($user->email, $user->username)
            ->subject('Test Email - M4V.ME');
});
```

## 4. Email Templates

### Welcome Email
- File: `resources/views/emails/welcome.blade.php`
- Gửi khi user đăng ký thành công
- Chứa thông tin tài khoản và hướng dẫn sử dụng

### Password Reset Email
- File: `resources/views/emails/password-reset.blade.php`
- Gửi khi user yêu cầu đặt lại mật khẩu
- Chứa link reset và thông tin bảo mật

## 5. Troubleshooting

### Lỗi thường gặp:

1. **"Connection could not be established"**
   - Kiểm tra MAIL_HOST và MAIL_PORT
   - Kiểm tra firewall/antivirus

2. **"Authentication failed"**
   - Kiểm tra MAIL_USERNAME và MAIL_PASSWORD
   - Với Gmail: sử dụng App Password

3. **"SSL/TLS error"**
   - Thử thay đổi MAIL_ENCRYPTION từ tls sang ssl
   - Hoặc thay đổi MAIL_PORT

4. **Email không được gửi**
   - Kiểm tra log: `storage/logs/laravel.log`
   - Test với MAIL_MAILER=log để debug

## 6. Production Settings

Cho production, khuyến nghị:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-production-smtp.com
MAIL_PORT=587
MAIL_USERNAME=production-email@yourdomain.com
MAIL_PASSWORD=secure-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="M4V.ME"
```

## 7. Queue cho Email

Để xử lý email bất đồng bộ:

```env
QUEUE_CONNECTION=database
```

Chạy queue worker:
```bash
php artisan queue:work
```
