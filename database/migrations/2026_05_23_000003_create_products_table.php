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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->string('name');
            $table->text('desc');
            $table->json('specs');
            $table->string('icon');
            $table->string('img')->nullable();
            $table->string('tolerance')->nullable();
            $table->string('capacity')->nullable();
            $table->string('speed')->nullable();
            $table->string('volume')->nullable();
            $table->text('auxiliary')->nullable();
            $table->text('safety')->nullable();
            $table->text('typical')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
