<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
