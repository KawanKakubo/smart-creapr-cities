<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_responses', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->string('population_band');
            $table->string('session')->default('manha');
            $table->json('answers');
            $table->timestamps();
            $table->index(['session', 'municipality']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_responses');
    }
};
