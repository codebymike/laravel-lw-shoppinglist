<?php

use App\Models\ListItem;
use function Livewire\Volt\{state};

state(['list','item','price' => 0]);

$add = function () {

    // coerce the price to a decimal
    if( is_numeric($this->price) && !is_float($this->price) ) {
        $this->price = number_format((float) $this->price, 2, '.', '');
    }

    $this->validate([
        'item' => 'required|string|max:255',
        'price' => 'decimal:2|min:0',
    ]);

    $this->list->items()->create([
        'title' => $this->item,
        'price' => $this->price,
    ]);

    $this->item = '';
    $this->price = 0;
};

$remove = function ( ListItem $item ) {
    $item->delete();
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

<div>
    <form wire:submit="add">
        <input wire:model="item" type="text" placeholder="Item Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
        <input wire:model="price" type="number" step="any" placeholder="Item Price" class="border border-gray-300 rounded-md p-2 text-slate-700">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add Item</button>
    </form>
    <div wire:sortable="updateListOrder">
        @foreach($list->sortedItems()->get() as $item)
            <div class="flex justify-between items-center border-b border-gray-300 py-2" wire:sortable.item="{{ $item->id }}">
                <button wire:click="toggleActive({{ $item->id }})">
                    {{ $item->is_active ? '✅' : '☑️' }}
                </button>
                <span class="text-lg {{ !$item->is_active ? 'line-through' : '' }}" wire:sortable.handle>
                    {{ $item->title }}
                </span>
                <span>£{{ $item->price }}</span>
                <button wire:click="remove({{ $item->id }})">❌</button>
            </div>
        @endforeach
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
