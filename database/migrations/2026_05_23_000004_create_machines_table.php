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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('spec');
            $table->integer('qty');
            $table->string('icon');
            $table->string('img')->nullable();
            $table->text('longDesc')->nullable();
            $table->string('origin')->nullable();
            $table->string('precision')->nullable();
            $table->text('safety')->nullable();
            $table->text('application')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
