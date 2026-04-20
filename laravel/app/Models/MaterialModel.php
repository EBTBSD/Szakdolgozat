<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $fillable = [
        'module_id',
        'file_name',
        'file_path'
        ];
}
