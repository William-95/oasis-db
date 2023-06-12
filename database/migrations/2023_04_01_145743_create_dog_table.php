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
        Schema::create('dog', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name');
            $table->string('sex');
            $table->string('race');
            $table->string('size');
            $table->date('date_birth');
            $table->integer('microchip')->unique();
            $table->date('date_entry');
            $table->string('img');
            $table->string('region');
            $table->string('structure');
            $table->string('contacts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dog');
    }
};
