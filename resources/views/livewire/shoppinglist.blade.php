<?php

use App\Models\ListItem;
use function Livewire\Volt\{state, computed};

state(['list','item','price']);

$total = computed(function () {
    // return $this->list->items()->where('is_active', true)->sum('price');
    return $this->list->items()->sum('price');
});

$add = function () {

    // laraval validation for prices is tricky, so doing it manually feels safer
    if (empty($this->price) || intval($this->price) < 0 || intval($this->price) >= PHP_INT_MAX) {
        $this->price = "0.00";
    }

    $this->validate([
        'item' => 'required|string|max:255',
    ]);

    $this->list->items()->create([
        'title' => $this->item,
        'price' => $this->price,
    ]);

    $this->item = '';
    $this->price = '';
};

$remove = function ( ListItem $item ) {
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

?>

<div class="max-w-[500px] mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">{{ $list->title }}</h1>
    <p class="mb-4">Manage your shopping list. Drag item to re-order</p>

    <div class="mb-4 justify-center items-center ">
        <form wire:submit="add">
            <input wire:model="item" type="text" placeholder="Item Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
            <input wire:model="price" type="number" step="any" placeholder="Item Price" class="border border-gray-300 rounded-md p-2 text-slate-700">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add Item</button>
            <div>
                @error('item') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
                @error('price') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
            </div>
        </form>
    </div>
    <div wire:sortable="updateListOrder">
        @foreach($list->sortedItems()->get() as $item)
            <div class="flex justify-between items-center border-b border-gray-300 py-2" wire:sortable.item="{{ $item->id }}">

                <button wire:click="toggleActive({{ $item->id }})">
                    {{ $item->is_active ? '⬜️' : '✅' }}
                </button>

                <div class="text-lg {{ !$item->is_active ? 'line-through' : '' }} flex gap-5 border-opacity-50 cursor-pointer" wire:sortable.handle>
                    <div>
                        {{ $item->title }}
                    </div>
                    <div>
                        £{{ $item->price }}
                    </div>
                </div>
                
                <button wire:click="remove({{ $item->id }})">❌</button>
            </div>
        @endforeach
    </div>
    <div class="flex justify-center items-center border-b border-gray-300 py-2 font-bold">
        Shopping List Total: £{{ $this->total }}
    </div>
    <style type="text/css">
        .draggable-source--is-dragging{
            background-color: #999;
            opacity: 0.9;
        }
        .draggable-mirror{
            display: none;
        }
    </style>
</div>
