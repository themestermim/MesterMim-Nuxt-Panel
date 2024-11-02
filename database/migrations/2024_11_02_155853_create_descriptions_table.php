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
        Schema::create('descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ایجاد ستون user_id
            $table->text('short_description')->nullable(); // ایجاد ستون توضیح کوتاه
            $table->text('long_description')->nullable(); // ایجاد ستون توضیح بلند
            $table->string('lang', 2)->default('fa');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE descriptions CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descriptions');
    }
};
