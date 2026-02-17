<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('accident_number')->unique();
            $table->string('plate_number');
            $table->string('status')->default('Submitted');
            $table->boolean('service_fee_paid')->default(false);
            $table->json('ai_result')->nullable();
            $table->json('final_result')->nullable();
            $table->text('notes')->nullable();
            $table->string('evaluator_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
