<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = "roles";
    protected $primary = "id";
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps =true;
}
