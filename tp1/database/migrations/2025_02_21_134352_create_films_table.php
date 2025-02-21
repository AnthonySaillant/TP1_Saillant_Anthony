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
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50)->notNullable();
            $table->string('release_year', 4)->notNullable();
            $table->integer('length')->unsigned()->notNullable();
            $table->text('description')->notNullable();
            $table->string('rating', 5)->notNullable();
            $table->integer('language_id')->unsigned()->notNullable();
            $table->string('special_features', 200)->notNullable();
            $table->string('image', 40)->notNullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
