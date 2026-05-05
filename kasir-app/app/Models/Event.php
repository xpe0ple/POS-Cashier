<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $primaryKey = 'event_id'; // 🔥 FIX
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['name'];

    // 🔥 RELASI KE TRANSAKSI
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'event_id');
    }
}
