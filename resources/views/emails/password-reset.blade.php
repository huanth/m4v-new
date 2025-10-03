<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - M4V.ME</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #036a95, #0288d1);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ffc107;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .reset-button:hover {
            background: linear-gradient(135deg, #c82333, #dc3545);
            transform: translateY(-2px);
        }
        .security-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .security-info h3 {
            margin: 0 0 10px 0;
            color: #dc3545;
            font-size: 16px;
        }
        .security-info ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .security-info li {
            margin: 5px 0;
            color: #666;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #036a95;
            text-decoration: none;
        }
        .expiry-warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        .code-display {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #036a95;
            border: 2px dashed #036a95;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>M4V.ME</h1>
            <p>Đặt lại mật khẩu</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <strong>⚠️ Yêu cầu đặt lại mật khẩu</strong><br>
                Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.
            </div>
            
            <p>Xin chào,</p>
            
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản <strong>{{ $user->username }}</strong> tại M4V.ME.</p>
            
            <p>Nếu bạn đã thực hiện yêu cầu này, vui lòng nhấn nút bên dưới để đặt lại mật khẩu:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Đặt lại mật khẩu
                </a>
            </div>
            
            <div class="expiry-warning">
                <strong>⏰ Lưu ý quan trọng:</strong><br>
                Liên kết đặt lại mật khẩu sẽ hết hạn sau <strong>60 phút</strong> kể từ khi email này được gửi.
            </div>
            
            <div class="security-info">
                <h3>🔒 Thông tin bảo mật:</h3>
                <ul>
                    <li>Liên kết này chỉ có thể sử dụng một lần</li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này</li>
                    <li>Mật khẩu mới phải có ít nhất 8 ký tự</li>
                    <li>Không chia sẻ liên kết này với bất kỳ ai</li>
                </ul>
            </div>
            
            <p><strong>Nếu nút không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:</strong></p>
            
            <div class="code-display">
                {{ $resetUrl }}
            </div>
            
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này. Tài khoản của bạn sẽ vẫn an toàn.</p>
            
            <p>Nếu bạn gặp bất kỳ vấn đề nào, đừng ngần ngại liên hệ với chúng tôi để được hỗ trợ.</p>
            
            <p>Trân trọng,<br><strong>Đội ngũ Bảo mật M4V.ME</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} M4V.ME. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
            <p>Nếu bạn có thắc mắc, vui lòng liên hệ: <a href="mailto:support@m4v.me">support@m4v.me</a></p>
        </div>
    </div>
</body>
</html>
