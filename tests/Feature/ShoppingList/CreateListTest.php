<?php

namespace Tests\Feature\ShoppingList;

use App\Models\User;
use Livewire\Volt\Volt;

test('Shopping list can be created', function () {
    $this->actingAs($user = User::factory()->create());

    $component = Volt::test('shopping_list.list')
        ->set('list_title', 'My Shopping List')
        ->call('addShoppingList');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseHas('shopping_lists', [
        'title' => 'My Shopping List',
        'user_id' => $user->id,
    ]);
});