<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = "permissions";
    protected $primary = "id";
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps =true;
}
