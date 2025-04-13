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
        Schema::create('matching_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained('question_groups')->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('matching_question_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('matching_option_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matching_answers');
    }
};
