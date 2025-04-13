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
        Schema::create('matching_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('passage_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained('question_groups')->onDelete('cascade');

            $table->unsignedBigInteger('question_id')->nullable();
            $table->foreign('question_id')
                  ->references('id')
                  ->on('questions')
                  ->onDelete('set null');
                  
            $table->text('option_text');
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_options');
    }
};
