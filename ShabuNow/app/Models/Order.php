<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function menus() : BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'order_details', 'order_id', 'menu_id')->withPivot(['quantity', 'status']);
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function transactions() : HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
