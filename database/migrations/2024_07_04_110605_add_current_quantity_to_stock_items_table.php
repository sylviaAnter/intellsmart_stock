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
        Schema::table('stock_items', function (Blueprint $table) {
            $table->bigInteger('original_quantity')->default(0);
            $table->bigInteger('current_quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_items', function (Blueprint $table) {
            //
        });
    }
};
