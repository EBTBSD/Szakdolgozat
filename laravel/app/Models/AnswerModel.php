<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnswerModel extends Model
{
    use HasFactory;
    protected $table = 'answers';
    protected $primaryKey = 'id';
    public $incremented = true;

    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct'
    ];
}