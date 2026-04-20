<?php

namespace App\Models;

use App\Models\MaterialModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModuleModel extends Model
{
    use HasFactory;
    protected $table = 'modules';
    protected $fillable = [
        'course_id',
        'module_title',
        'order_index'
        ];

    public function course() {
        return $this->belongsTo(CoursesModel::class, 'course_id');
    }

    public function assignments() {
        return $this->hasMany(AssignmentModel::class, 'module_id');
    }

    public function materials() {
        return $this->hasMany(MaterialModel::class, 'module_id');
    }
}