<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;


class Admin extends Model
{
    use HasFactory;
    protected $table = 'admins';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $dateFormat = 'M-d-y H:i:s';
    protected $attributes = [
        'firstname' => '',
        'lastname' => '',
        'position' => '',
        'email' => '',
        'contact_number' => '',
    ];

    protected $fillable = [
        'firstname',
        'lastname',
        'position',
        'email',
        'contact_number',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }
    public function getContactNumberAttribute($value)
    {
        return '+254' . substr($value, 1);
    }
    public function setContactNumberAttribute($value)
    {
        $this->attributes['contact_number'] = preg_replace('/^0/', '', $value);
    }
    public function getPositionAttribute($value)
    {
        return ucfirst($value);
    }
    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = strtolower($value);
    }
    public function getEmailAttribute($value)
    {
        return strtolower($value);
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    
}
