<?php
use \App\Models\ShoppingList;
use function Livewire\Volt\{state};


state(['list']);

$add = function () {
    $this->validate([
        'list' => 'required|string|max:255',
    ]);
    ShoppingList::create([
        'user_id' => auth()->id(),
        'title' => $this->list
    ]);
    $this->list = '';
};

?>

<div>
    <form wire:submit="add">
        <input type="text" wire:model="list" placeholder="Shopping List Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create List</button>
    </form>
</div>
