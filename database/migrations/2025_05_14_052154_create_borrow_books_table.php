<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function down(): void
    {
        Schema::table('borrow_books', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });
        Schema::dropIfExists('borrow_books');
        Schema::dropIfExists('books');
    }

    public function up(): void
    {
        Schema::create('borrow_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['active', 'overdue', 'returned'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
 
};
