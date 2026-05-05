<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $primaryKey = 'transaction_item_id'; // 🔥 TAMBAH
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'price'
    ];

    // 🔗 RELASI KE PRODUCT
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // 🔥 FIX
    }

    // 🔗 RELASI KE TRANSACTION
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id'); // 🔥 FIX
    }
}
