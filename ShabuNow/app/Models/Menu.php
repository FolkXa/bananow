<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function orders() : BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_details', 'menu_id', 'order_id')->withPivot(['quantity', 'status']);
    }
}

