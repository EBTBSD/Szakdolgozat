<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursesModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    // protected $keyType = 'bigint';
    public $incremented = true;

    protected $fillable = [
        'creator_username',
        'course_name',
        'course_type',
        'course_img_path',
        'course_users',
    ];
}
