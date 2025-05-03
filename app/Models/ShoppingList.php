<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    //
    public $guarded = [];

    public function items()
    {
        return $this->hasMany(ListItem::class);
    }

    public function sortedItems()
    {
        return $this->items()->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
