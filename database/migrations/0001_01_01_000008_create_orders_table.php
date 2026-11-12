<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->date('lesson_date');
            $table->tinyInteger('hours');
            $table->unsignedInteger('base_price');
            $table->unsignedInteger('extra_student_price');
            $table->unsignedInteger('total_price');
            $table->tinyInteger('status')->default(1);
            $table->string('payment_id')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('lesson_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
