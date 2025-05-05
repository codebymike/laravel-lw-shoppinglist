<?php

use function Livewire\Volt\{state, with};

state(['list_title']);

with([
    'lists' => fn() => auth()->user()->shoppingLists()->get(),
]);

$addShoppingList = function () {
    $this->validate([
        'list_title' => 'required|string|max:255',
    ]);
    auth()->user()->shoppingLists()->create([
        'title' => $this->list_title
    ]);
    $this->list = '';
};

?>

<div class="max-w-[500px] mx-auto p-4">

    <h1 class="text-2xl font-bold mb-4">Shopping Lists</h1>
    <p class="mb-4">Create and manage your shopping lists.</p>

    <div class="mb-4 justify-center items-center ">
        <form wire:submit="addShoppingList">
            <input type="text" wire:model="list_title" placeholder="Shopping List Name" class="border border-gray-300 rounded-md p-2 text-slate-700">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Create New List</button>
            <div>
                @error('list') <span class="block text-red-700 bg-pink-200 text-center">{{ $message }}</span> @enderror 
            </div>
        </form>
    </div>

    <div class="mt-10">
        @foreach($lists as $list)
            <div class="flex justify-between items-center border-b border-gray-300 py-4">
                <div>
                    {{ $list->title }}
                </div>
                <div>
                    <a href="/list/{{ $list->id }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        View List
                    </a>
                </div>
                    
                </span>
            </div>
        @endforeach
    </div>
</div>
