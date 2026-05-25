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
        Schema::table('products', function (Blueprint $table) {
            $table->string('detail_img1')->nullable()->after('img');
            $table->string('detail_img2')->nullable()->after('detail_img1');
            $table->string('detail_img3')->nullable()->after('detail_img2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['detail_img1', 'detail_img2', 'detail_img3']);
        });
    }
};
