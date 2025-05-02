<?php
use function Livewire\Volt\{state, with};


state(['list']);

with([
    'lists' => fn() => auth()->user()->shoppingLists()->get(),
]);

$add = function () {
    $this->validate([
        'list' => 'required|string|max:255',
    ]);
    auth()->user()->shoppingLists()->create([
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
    <div>
        @foreach($lists as $list)
            <div class="flex justify-between items-center border-b border-gray-300 py-2">
                <span class="text-lg">
                    <a href="/list/{{ $list->id }}">
                        {{ $list->title }}
                    </a>
                </span>
            </div>
        @endforeach
    </div>
</div>
