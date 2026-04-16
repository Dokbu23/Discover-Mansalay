<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resort_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type', 100)->nullable();
            $table->integer('capacity')->default(1);
            $table->decimal('price_per_night', 10, 2)->default(0);
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
};
