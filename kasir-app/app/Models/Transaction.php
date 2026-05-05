<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'transaction_id'; // 🔥 WAJIB
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id', // 🔥 TAMBAH
        'event_id',
        'total',
        'payment_method',
        'amount_paid',
        'change'
    ];

    // 🔗 RELASI KE ITEMS
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id'); // 🔥 FIX
    }

    // 🔗 RELASI KE EVENT
    public function eventRel()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // 🔗 RELASI KE USER (BONUS BIAR LENGKAP)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
