<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SubmissionModel extends Model
{
    protected $table = 'submissions';
    protected $fillable = [
        'assignment_id',
        'user_id',
        'achieved_points',
        'grade',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}