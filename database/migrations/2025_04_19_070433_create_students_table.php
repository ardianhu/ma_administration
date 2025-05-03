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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('nis')->unique()->nullable();
            $table->string('name');
            $table->enum('gender', ["L", "P"]);
            $table->string('address')->nullable();
            $table->string('dob')->nullable();
            $table->integer('th_child')->nullable();
            $table->integer('siblings_count')->nullable();
            $table->string('education')->nullable();
            $table->integer('nisn')->unique()->nullable();
            $table->date('registration_date');
            $table->date('drop_date')->nullable();
            $table->string('drop_reason')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_dob')->nullable();
            $table->string('father_address')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_job')->nullable();
            $table->enum('father_alive', ["Hidup", "Meninggal"])->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_dob')->nullable();
            $table->string('mother_address')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_job')->nullable();
            $table->enum('mother_alive', ["Hidup", "Meninggal"])->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_dob')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_education')->nullable();
            $table->string('guardian_job')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->unsignedBigInteger('dorm_id')->nullable();
            $table->unsignedBigInteger('islamic_class_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
