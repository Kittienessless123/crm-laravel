<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->string('product_id')->nullable()->comment('Внешний ID из прайса поставщика');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};