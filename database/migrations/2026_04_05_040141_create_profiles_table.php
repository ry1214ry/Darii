<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('job_title')->nullable();
            $table->text('short_intro')->nullable();
            $table->longText('about_me')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('profile_video')->nullable();
            $table->string('experience')->nullable();
            $table->string('education')->nullable();
            $table->text('technologies')->nullable();
            $table->text('goals')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};