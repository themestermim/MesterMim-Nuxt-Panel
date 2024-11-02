<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDescriptions extends Model
{
    use HasFactory;

    // مشخص کردن نام جدول (در صورت نیاز)
    protected $table = 'descriptions';

    // فیلدهایی که قابل پر شدن هستند
    protected $fillable = [
        'user_id',
        'short_description',
        'long_description',
        'lang',
    ];

    // تعریف ارتباط با مدل User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
