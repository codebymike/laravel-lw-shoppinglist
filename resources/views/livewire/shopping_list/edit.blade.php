<?php

use App\Models\ListItem;
use App\Models\ShoppingList;
use function Livewire\Volt\{state, computed, mount};

state(['list','item_title','item_price','price_limit']);

mount(function () {
    $this->price_limit = $this->list->price_limit;
});

$total = computed(function () {
    $total_price = $this->list->items()->sum('price');
    return number_format($total_price, 2);
});

$over_limit = computed(function () {
    return $this->price_limit > 0 && $this->total > $this->price_limit;
});

$addListItem = function () {

    // allow blank price, but default to 0.00
    if (empty($this->item_price)) {
        $this->item_price = "0.00";
    } else {
        // enforce 2 decimal places
        $this->item_price = number_format((float)$this->item_price, 2, '.', '');
    }

    $this->validate([
        'item_title' => 'required|string|max:255',
        'item_price' => 'required|numeric|min:0|max:999999.99',
    ]);

    $this->list->items()->create([
        'title' => $this->item_title,
        'price' => $this->item_price,
    ]);

    $this->item_title = '';
    $this->item_price = '';
};

$removeListItem = function ( ListItem $item ) {
    $item->delete();
    session()->flash('message', 'Item removed from list.');
};

$toggleActive = function ( ListItem $item ) {
    $item->is_active = !$item->is_active;
    $item->save();
};

$updateListOrder = function ( array $items ) {
    foreach ($items as $item) {
        ListItem::whereId($item['value'])->update(['order' => $item['order']]);
    }
};

$updatePriceLimit = function () {
    $this->validate([
        'price_limit' => 'required|numeric|min:0|max:999999.99',
    ]);

    $this->list->price_limit = $this->price_limit;
    $this->list->save();
};

?>

<div class="max-w-[500px] mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">{{ $list->title }}</h1>
    <p class="mb-4">Manage your shopping list. Drag item to re-order</p>

    <div class="mb-4 justify-center items-center ">
        <form wire:submit="addListItem">
            <input wire:model="item_title" type="text" placeholder="Item Name" class="border border-gray-300 rounded-md p-2 text-slate-700" aria-label="List Item Name" />
            <input wire:model="item_price" type="number" step="any" placeholder="Item Price" class="border border-gray-300 rounded-md p-2 text-slate-700" aria-label="List Item Price" />
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" aria-label="Add Item to List">Add Item</button>
            <div>
                @error('item_title') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
                @error('item_price') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
            </div>
        </form>
    </div>
    <div wire:sortable="updateListOrder">
        @foreach($list->sortedItems()->get() as $item)
            <div class="flex justify-between items-center border rounded-md border-gray-300 p-2 mb-1  {{ !$item->is_active ? 'bg-black opacity-20' : '' }}" wire:sortable.item="{{ $item->id }}">

                <div class="cursor-pointer" wire:sortable.handle aria-label="Drag Item to Reorder">
                    <span class="cursor-move">☰</span>
                </div>

                <button wire:click="toggleActive({{ $item->id }})" class="" aria-label="Toggle Item Checked">
                    {{ $item->is_active ? '⬜️' : '✅' }}
                </button>

                <div class="text-lg w-[13rem]">
                    <div class="flex justify-between items-center">
                        <div>
                            {{ $item->title }}
                        </div>
                        <div>
                            &pound;{{ $item->price }}
                        </div>
                    </div>
                </div>
                
                <button class="" wire:click="removeListItem({{ $item->id }})" wire:confirm="Confirm delete item?" aria-label="Remove Item from List">❌</button>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center items-center border-b border-gray-300 py-2 my-2 font-bold {{ $this->over_limit ? 'bg-red-400' : 'bg-green-500' }}">
        Shopping List Total: &pound;{{ $this->total }} 
        @if($this->over_limit)
            <span class="text-red-700 pl-2"> - Over Limit!</span>
        @endif
    </div>

    <div class="flex justify-center items-center border-b border-gray-300 py-2 font-bold">
        <div class="w-full text-center">
            <form wire:submit="updatePriceLimit">
                <label for="price_limit">Price Limit: &pound;</label>
                <input id="price_limit" wire:model="price_limit" type="number" step="any" placeholder="Price Limit" class="border border-gray-300 rounded-md p-2 text-slate-700" aria-label="List Price Limit" />
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" aria-label="Update Price Limit">Update Limit</button>
                <div>
                    @error('price_limit') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
                </div>
            </form>
        </div>
    </div>

    <style type="text/css">
        .draggable-source--is-dragging{
            background-color: lightgreen;
            opacity: 0.9;
        }
        .draggable-mirror{
            display: none;
        }
    </style>
</div>
