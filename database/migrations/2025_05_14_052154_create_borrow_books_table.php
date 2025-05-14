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
        Schema::create('borrow_books', function (Blueprint $table) {
            $table->id('Borrow_Id');
            $table->foreignId('User_Id')->references('Student_Id')->on('students')->onDelete('cascade');
            $table->foreignId('Book_Id')->references('Book_Id')->on('books')->onDelete('cascade');
            $table->date('Borrow_Date');
            $table->date('Return_Date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_books');
    }
};
