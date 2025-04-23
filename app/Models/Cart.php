<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['table_id'];

    public function user()
    {
        return $this->belongsTo(Tables::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
