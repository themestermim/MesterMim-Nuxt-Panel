<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('type_id')->index(); // شناسه‌ی مشترک برای گروه‌بندی رکوردها
            $table->string('image')->nullable(); // مسیر یا نام تصویر
            $table->string('company_name')->nullable(); // نام شرکت
            $table->date('arrival_date')->nullable(); // تاریخ ورود
            $table->date('exit_date')->nullable(); // تاریخ پایان
            $table->string('role')->nullable(); // نقش یا وظیفه
            $table->string('lang', 2)->default('fa'); // زبان
            $table->timestamps();
        });

        DB::statement('ALTER TABLE experiences CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
