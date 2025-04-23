<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['tables_id', 'status', 'total_price'];

    public function tables()
    {
        return $this->belongsTo(Tables::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

