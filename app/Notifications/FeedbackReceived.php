<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeedbackReceived extends Notification
{
    use Queueable;

    public $feedback;

    /**
     * Create a new notification instance.
     */
    public function __construct($feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Feedback Baru',
            'message' => 'Pengajar ' . $this->feedback->pengajar->name . ' memberikan komentar terbaru.',
            'link' => $notifiable->role == 'siswa' 
                ? route('siswa.quiz.result', $this->feedback->quiz_result_id) 
                : route('wali.nilai'),
            'type' => 'feedback',
            'content' => \Illuminate\Support\Str::limit($this->feedback->komentar, 50),
            'created_at' => now(),
        ];
    }
}
