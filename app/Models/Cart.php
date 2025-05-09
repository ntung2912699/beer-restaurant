<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['table_id'];

    public function table()
    {
        return $this->belongsTo(Tables::class, 'table_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
