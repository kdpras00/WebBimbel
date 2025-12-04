<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wali_id',
        'jurusan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function anak()
    {
        return $this->hasMany(User::class, 'wali_id');
    }

    public function kelasSiswa()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa', 'siswa_id', 'kelas_id')
            ->withPivot('jurusan');
    }

    public function kelasPengajar()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_pengajar', 'pengajar_id', 'kelas_id')
            ->withPivot('mapel_id');
    }

    public function mapelDiajar()
    {
        return $this->belongsToMany(Mapel::class, 'kelas_pengajar', 'pengajar_id', 'mapel_id')
            ->withPivot('kelas_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'pengajar_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'pengajar_id');
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class, 'siswa_id');
    }

    public function quizSessions()
    {
        return $this->hasMany(QuizSession::class, 'siswa_id');
    }

    public function point()
    {
        return $this->hasOne(Point::class);
    }

    public function feedbackDiberikan()
    {
        return $this->hasMany(Feedback::class, 'pengajar_id');
    }

    public function feedbackDiterima()
    {
        return $this->hasMany(Feedback::class, 'siswa_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPengajar()
    {
        return $this->role === 'pengajar';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isWali()
    {
        return $this->role === 'wali';
    }

    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }
}
