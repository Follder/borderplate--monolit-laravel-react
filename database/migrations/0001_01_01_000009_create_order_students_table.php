<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price');
            $table->boolean('is_primary')->default(false);
            $table->string('receipt_url')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_students');
    }
};
