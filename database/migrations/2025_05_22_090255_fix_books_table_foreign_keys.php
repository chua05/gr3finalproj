<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixBooksTableForeignKeys extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First drop the foreign key constraint
        Schema::table('borrow_books', function (Blueprint $table) {
            $table->dropForeign(['book_id']); // Make sure this matches your constraint name
        });

        // Then recreate the foreign key with cascade
        Schema::table('borrow_books', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop and recreate the foreign key without cascade
        Schema::table('borrow_books', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->foreign('book_id')->references('id')->on('books');
        });
    }
}