<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id'; // 🔥 FIX
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name', 'price', 'stock', 'image', 'event_id'];

    // 🔗 relasi ke transaction_items
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'product_id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/50';
        }

        if (str_starts_with($this->image, 'products/')) {
            return asset('storage/' . $this->image);
        }

        return asset('images/' . $this->image);
    }
}
