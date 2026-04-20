<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsersModel extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id';
    #protected $keyType = 'bigint';
    public $incremented = true;

    protected $fillable = [
        'username',
        'lastname',
        'firstname',
        'email',
        'password',
    ];
}
