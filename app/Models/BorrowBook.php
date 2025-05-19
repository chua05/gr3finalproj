<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowBook extends Model
{
    use HasFactory;

    protected $table = 'borrows';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $dateFormat = 'M-d-y H:i:s';

    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_at',
        'returned_at',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function getStatusAttribute($value)
    {
        return $value ? 'Returned' : 'Not Returned';
    }
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ? 1 : 0;
    }
    public function getBorrowedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('M-d-y H:i:s');
    }
    public function setBorrowedAtAttribute($value)
    {
        $this->attributes['borrowed_at'] = \Carbon\Carbon::parse($value)->format('M-d-y H:i:s');
    }   
}
