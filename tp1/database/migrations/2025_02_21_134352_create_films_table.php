<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->year('release_year');
            $table->integer('length');
            $table->text('description');
            $table->string('rating', 5);
            $table->foreignId('language_id');
            $table->string('special_features', 200);
            $table->string('image', 40);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('films');
    }
};
