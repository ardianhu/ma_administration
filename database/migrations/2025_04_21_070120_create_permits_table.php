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
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('permit_type', ['sakit', 'kepentingan']);
            $table->datetime('leave_on');
            $table->datetime('back_on');
            $table->datetime('arrive_on')->nullable();
            $table->string('reason');
            $table->string('destination');
            $table->integer('extended_count')->default(0);
            $table->unsignedBigInteger('academic_year_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
