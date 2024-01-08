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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->text('desc')->nullable();
            $table->string('img')->nullable(false);
            $table->string('status', 20);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();
            $table->foreign('category_id')->on('categories')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
