<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->index();
            $table->text('prompt');
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->string('url')->nullable();
            $table->unsignedTinyInteger('change_degree')->nullable();
            $table->string('task_id')->nullable()->index();
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
