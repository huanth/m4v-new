<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Chào mừng đến với M4V.ME - Cộng đồng đích thực')
            ->greeting('Xin chào ' . $notifiable->username . '!')
            ->line('Cảm ơn bạn đã đăng ký tài khoản tại M4V.ME - Cộng đồng đích thực của chúng tôi!')
            ->line('Chúng tôi rất vui mừng được chào đón bạn tham gia vào cộng đồng.')
            ->action('Truy cập Trang Cá Nhân', url('/dashboard'))
            ->line('Với tài khoản M4V.ME, bạn có thể:')
            ->line('• Tham gia thảo luận trong các chủ đề yêu thích')
            ->line('• Chia sẻ kiến thức và kinh nghiệm')
            ->line('• Kết nối với các thành viên khác')
            ->line('• Mua bán các sản phẩm uy tín')
            ->line('• Truy cập M4V Central - trung tâm thông tin')
            ->line('• Tham gia các hoạt động cộng đồng')
            ->line('Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi.')
            ->line('Chúc bạn có những trải nghiệm tuyệt vời tại M4V.ME!')
            ->salutation('Trân trọng, Đội ngũ M4V.ME');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
