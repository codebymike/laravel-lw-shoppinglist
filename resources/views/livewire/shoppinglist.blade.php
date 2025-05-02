<?php

use function Livewire\Volt\{state};

state(['list','item']);

$add = function () {
    $this->validate([
        'item' => 'required|string|max:255',
    ]);

    $this->list->items()->create([
        'title' => $this->item
    ]);

    $this->item = '';
};

?>

<div>
    <form wire:submit="add">
        <input type="text" wire:model="item" placeholder="Item Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Add Item</button>
    </form>
    <div>
        @foreach($list->items as $item)
            <div class="flex justify-between items-center border-b border-gray-300 py-2">
                <span class="text-lg">
                    {{ $item->title }}
                </span>
            </div>
        @endforeach
    </div>
</div>
