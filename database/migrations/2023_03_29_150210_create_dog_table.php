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
            $table->increments('id_dog')->unique();
            $table->string('name')->nullable();
            $table->string('sex');
            $table->string('race');
            $table->string('size');
            $table->date('date_birth')->format('d/m/Y')->nullable();
            $table->integer('microchip')->required(15)->unique();
            $table->date('date_entry')->format('d/m/Y');
            $table->string('img');
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
