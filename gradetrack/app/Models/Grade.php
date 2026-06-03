<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id','teacher_id','subject','score','semester','school_year',
    ];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }

    public function getLetterGradeAttribute(): string {
        $s = $this->score;
        if ($s >= 90) return 'A';
        if ($s >= 80) return 'B';
        if ($s >= 75) return 'C';
        if ($s >= 70) return 'D';
        return 'F';
    }

    public function getStatusAttribute(): string {
        return $this->score >= 75 ? 'Pass' : 'Fail';
    }

    public function getLetterColorAttribute(): string {
        return match($this->letter_grade) {
            'A' => 'success', 'B' => 'primary', 'C' => 'warning', default => 'danger',
        };
    }
}
