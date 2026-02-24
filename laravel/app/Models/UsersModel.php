<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'bigint';
    public $incremented = true;

    protected $fillable = [
        'username',
        'lastname',
        'firstname',
        'email',
        'password',
    ];
}
