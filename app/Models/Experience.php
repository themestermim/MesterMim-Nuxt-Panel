<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'company_name',
        'arrival_date',
        'exit_date',
        'role',
        'image',
        'lang',
    ];
}
