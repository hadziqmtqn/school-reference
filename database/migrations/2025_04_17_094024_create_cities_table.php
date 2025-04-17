<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->string('code');
            $table->string('name');
            $table->unsignedInteger('province_id');
            $table->string('url')->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
