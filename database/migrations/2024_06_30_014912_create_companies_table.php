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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('owner_id');
            $table->text('name');
            $table->text('email')->nulllable();
            $table->text('logo')->nulllable();
            $table->text('website')->nulllable();
            $table->text('about')->nulllable();
            $table->string('status')->nulllable();
            $table->date('license_expire')->nulllable();
            $table->text('address')->nulllable();
            $table->text('phone_number')->nulllable();
            $table->text('phone_number_2')->nulllable();
            $table->text('pobox')->nulllable();
            $table->text('color')->nulllable();
            $table->text('slogan')->nulllable();
            $table->text('facebook')->nulllable();
            $table->text('twitter')->nulllable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
