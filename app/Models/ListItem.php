<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    //
    public $guarded = [];
    
    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }
}
