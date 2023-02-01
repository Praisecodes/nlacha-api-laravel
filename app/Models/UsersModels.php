<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersModels extends Model
{
    use HasFactory;
    protected $fillable = [
        "fullname",
        "username",
        "email",
        "userPassword"
    ];

    protected $table = 'users';

    const CREATED_AT = "dateCreated";
    const UPDATED_AT = null;
}
