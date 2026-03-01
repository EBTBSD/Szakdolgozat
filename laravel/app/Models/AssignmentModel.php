<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentModel extends Model
{
    protected $table = 'assignment';
    protected $primaryKey = 'id';
    public $incremented = true;

    protected $fillable = [
        'course_id',
        'creator_username',
        'user_username',
        'assignment_name',
        'assignment_type',
        'assignment_finnished',
        'assignment_max_point',
        'assignment_succed_point',
        'assignment_grade',
        'assignment_deadline',
        'assignment_accessible'
    ];
}
