<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('critics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('film_id');
            $table->decimal('score', 3, 1);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('critics');
    }
};
