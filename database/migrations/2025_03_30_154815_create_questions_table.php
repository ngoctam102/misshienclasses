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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('passage_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('question_groups')->onDelete('cascade');
            $table->text('question_content');
            $table->text('explanation')->nullable();
            $table->enum('question_type', ['fill_in_blank', 'fill_in_blank_with_options', 'correct_answer', 'true_false_not_given', 'multiple_choice', 'matching']);
            $table->integer('order')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
