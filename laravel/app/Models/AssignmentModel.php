<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentModel extends Model
{
    use HasFactory;
    protected $table = 'assignments';
    protected $fillable = [
        'module_id',
        'assignment_name',
        'assignment_type',
        'assignment_max_point',
        'assignment_deadline',
        'assignment_accessible'
    ];

    public function module() {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function submissions() {
        return $this->hasMany(Submission::class, 'assignment_id');
    }
}