<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerModel extends Model
{
    protected $table = 'answers';
    protected $primaryKey = 'id';
    public $incremented = true;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct'
    ];
}