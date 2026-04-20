<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursesModel extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = 'id';
    // protected $keyType = 'bigint';
    public $incremented = true;

    protected $fillable = [
        'creator_id',
        'creator_username',
        'course_name',
        'course_type',
        'course_img_path',
        'invite_code'
    ];

    public function modules() {
        return $this->hasMany(Module::class, 'course_id')->orderBy('order_index');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_enrollments', 'course_id', 'user_id')
                    ->withTimestamps();
    }
}
