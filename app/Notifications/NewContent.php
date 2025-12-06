<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContent extends Notification
{
    use Queueable;

    public $content;
    public $type; // 'materi' or 'quiz'

    /**
     * Create a new notification instance.
     */
    public function __construct($content, $type = 'materi')
    {
        $this->content = $content;
        $this->type = $type;
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
        $title = $this->type === 'quiz' ? 'Quiz Baru Tersedia' : 'Materi Baru Diupload';
        $link = $this->type === 'quiz' 
            ? route('siswa.quiz.show', $this->content->id) 
            : route('siswa.materi.index'); // Adjust if detail view exists

        return [
            'title' => $title,
            'message' => $this->content->judul . ' - ' . $this->content->mapel->nama,
            'link' => $link,
            'type' => $this->type,
            'created_at' => now(),
        ];
    }
}
