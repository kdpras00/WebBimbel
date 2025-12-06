<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuizGraded extends Notification
{
    use Queueable;

    public $result;

    /**
     * Create a new notification instance.
     */
    public function __construct($result)
    {
        $this->result = $result;
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
            'title' => 'Nilai Quiz Keluar',
            'message' => 'Nilai untuk quiz "' . $this->result->quiz->judul . '" sudah tersedia.',
            'link' => route('siswa.quiz.index'), // Or detail result page
            'type' => 'grade',
            'grade' => $this->result->nilai, // Optional extra data
            'created_at' => now(),
        ];
    }
}
