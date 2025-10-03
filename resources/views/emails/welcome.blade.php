<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng đến với M4V.ME</title>
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
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #036a95;
            font-weight: 600;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #036a95;
        }
        .user-info h3 {
            margin: 0 0 10px 0;
            color: #036a95;
            font-size: 16px;
        }
        .user-info p {
            margin: 5px 0;
            color: #666;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #036a95, #0288d1);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            background: linear-gradient(135deg, #0288d1, #036a95);
            transform: translateY(-2px);
        }
        .features {
            margin: 30px 0;
        }
        .features h3 {
            color: #036a95;
            margin-bottom: 15px;
        }
        .features ul {
            list-style: none;
            padding: 0;
        }
        .features li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
        }
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
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
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #036a95;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>M4V.ME</h1>
            <p>Cộng đồng đích thực</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                Chào mừng {{ $user->username }} đến với M4V.ME! 🎉
            </div>
            
            <p>Xin chào <strong>{{ $user->username }}</strong>,</p>
            
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>M4V.ME</strong> - Cộng đồng đích thực của chúng tôi! Chúng tôi rất vui mừng được chào đón bạn tham gia vào cộng đồng.</p>
            
            <div class="user-info">
                <h3>Thông tin tài khoản của bạn:</h3>
                <p><strong>Tên đăng nhập:</strong> {{ $user->username }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <div class="features">
                <h3>Với tài khoản M4V.ME, bạn có thể:</h3>
                <ul>
                    <li>Tham gia thảo luận trong các chủ đề yêu thích</li>
                    <li>Chia sẻ kiến thức và kinh nghiệm</li>
                    <li>Kết nối với các thành viên khác</li>
                    <li>Mua bán các sản phẩm uy tín</li>
                    <li>Truy cập M4V Central - trung tâm thông tin</li>
                    <li>Tham gia các hoạt động cộng đồng</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="cta-button">
                    Truy cập Trang Cá Nhân
                </a>
            </div>
            
            <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi. Chúng tôi luôn sẵn sàng hỗ trợ bạn!</p>
            
            <p>Chúc bạn có những trải nghiệm tuyệt vời tại M4V.ME!</p>
            
            <p>Trân trọng,<br><strong>Đội ngũ M4V.ME</strong></p>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Instagram</a>
            </div>
            <p>© {{ date('Y') }} M4V.ME. Tất cả quyền được bảo lưu.</p>
            <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>
</html>
