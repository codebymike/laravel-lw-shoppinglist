<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(ShoppingList $shoppingList)
    {
        return view('shopping_list.edit', [
            'list' => $shoppingList,
        ]);
    }
}
