<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionModel extends Model
{
    protected $table = 'submissions';
    protected $fillable = [
        'assignment_id',
        'user_id',
        'submitted_file_path',
        'text_answer', 
        'achieved_points',
        'teacher_feedback'
    ];

    public function assignment() {
        return $this->belongsTo(AssignmentModel::class, 'assignment_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}