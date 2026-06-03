<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','role','status',
        'phone','address','gender','profile_picture',
    ];
    protected $hidden = ['password','remember_token'];

    // ── Relationships ──────────────────────────────────────────
    public function gradesAsStudent() {
        return $this->hasMany(Grade::class, 'student_id');
    }
    public function gradesAsTeacher() {
        return $this->hasMany(Grade::class, 'teacher_id');
    }

    // ── Role helpers ───────────────────────────────────────────
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isTeacher(): bool  { return $this->role === 'teacher'; }
    public function isStudent(): bool  { return $this->role === 'student'; }
    public function isApproved(): bool { return $this->status === 'approved'; }

    // ── Accessors ──────────────────────────────────────────────
    public function getInitialsAttribute(): string {
        $words = explode(' ', $this->name);
        return strtoupper(
            substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : '')
        );
    }

    public function getStatusBadgeAttribute(): string {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'warning',
        };
    }

    public function getRoleBadgeAttribute(): string {
        return match($this->role) {
            'admin'   => 'primary',
            'teacher' => 'info',
            default   => 'secondary',
        };
    }
}
