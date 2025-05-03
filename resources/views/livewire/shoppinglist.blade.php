<?php

use App\Models\ListItem;
use function Livewire\Volt\{state};

state(['list','item']);

// $sortedItems = function () {
    // return $this->list->items()->)->get();
// };

$add = function () {
    $this->validate([
        'item' => 'required|string|max:255',
    ]);

    $this->list->items()->create([
        'title' => $this->item
    ]);

    $this->item = '';
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

// how do I sort the order of the items in $list->items? when $list->items is in a laravel volt component?
// $sortedItems = $list->items()->orderBy('order')->get()

?>

<div>
    <form wire:submit="add">
        <input type="text" wire:model="item" placeholder="Item Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add Item</button>
    </form>
    <div wire:sortable="updateListOrder">
        @foreach($list->sortedItems()->get() as $item)
            <div class="flex justify-between items-center border-b border-gray-300 py-2" wire:sortable.item="{{ $item->id }}" wire:sortable.handle>
                <button wire:click="toggleActive({{ $item->id }})">
                    {{ $item->is_active ? '✅' : '☑️' }}
                </button>
                <span class="text-lg {{ !$item->is_active ? 'line-through' : '' }}">
                    {{ $item->title }}
                </span>
                <button wire:click="remove({{ $item->id }})">❌</button>
            </div>
        @endforeach
    </div>
</div>
