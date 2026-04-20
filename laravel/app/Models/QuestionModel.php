<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionModel extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected $primaryKey = 'id';
    public $incremented = true;

    protected $fillable = [
        'assignment_id',
        'question_text',
        'question_type',
        'question_points'
    ];
    public function answers()
    {
        return $this->hasMany(AnswerModel::class, 'question_id');
    }
}