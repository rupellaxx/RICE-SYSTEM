<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rice extends Model
{

protected $table = 'rices';
    protected $fillable = [
        'name',
        'price',
        'unit',
        'description',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}