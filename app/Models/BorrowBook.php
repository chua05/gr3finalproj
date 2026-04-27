<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowBook extends Model
{
    use HasFactory;

    protected $table = 'borrow_books';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $dateFormat = 'M-d-y H:i:s';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];
    /**
     * Get the user that owns the borrowing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that is borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if the borrowing is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->return_date === null && now()->gt($this->due_date);
    }
    /**
     * Check if the book is returned.
     *
     * @return bool
     */
    public function isReturned()
    {
        return $this->status === 'returned';
    }
    /**
     * Check if the book is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
    /**
     * Check if the book is overdue.
     *
     * @return bool
     */
    public function isActiveOrOverdue()
    {
        return $this->isActive() || $this->isOverdue();
    }
    
    /**
     * Get the borrow date in a formatted way.
     *
     * @return string
     */
    public function getFormattedBorrowDate()
    {
        return $this->borrow_date->format('M d, Y');
    }
    /**
     * Get the due date in a formatted way.
     *
     * @return string
     */
    public function getFormattedDueDate()
    {
        return $this->due_date->format('M d, Y');
    }
    /**
     * Get the return date in a formatted way.
     *
     * @return string|null
     */
    public function getFormattedReturnDate()
    {
        return $this->return_date ? $this->return_date->format('M d, Y') : null;
    }
    /**
     * Get the status of the borrowing.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }






    



    

}
