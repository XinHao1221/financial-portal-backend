<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_income',
        'amount',
        'description',
        'account_id',
        'category_id',
        'datetime',
        'user_id'
    ];

    protected function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    protected function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    protected function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }
}
