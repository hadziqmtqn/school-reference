<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->string('name');
            $table->string('npsn')->nullable();
            $table->string('street')->nullable();
            $table->string('village')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('form_of_education_id');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->restrictOnDelete();
            $table->foreign('form_of_education_id')->references('id')->on('form_of_education')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
