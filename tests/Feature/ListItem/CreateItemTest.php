<?php

namespace Tests\Feature\ListItem;

use App\Models\User;
use App\Models\ShoppingList;
use Livewire\Volt\Volt;

test('ListItem can be created', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    $component = Volt::test('shoppinglist', [ 'list' => $shoppingList ])
        ->set('item_title', 'My List Item')
        ->set('item_price', '10.00')
        ->call('addListItem');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseHas('list_items', [
        'title' => 'My List Item',
        'price' => '10.00',
        'shopping_list_id' => $shoppingList->id,
    ]);
});

test('ListItem is listed in the shopping list', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create a new list item
    $listItem = $shoppingList->items()->create([
        'title' => 'My List Item',
        'price' => '10',
    ]);

    $component = Volt::test('shoppinglist', [ 'list' => $shoppingList ]);

    $component
        ->assertSee($listItem->title)
        ->assertSee($listItem->price);
});

test('ListItem can be deleted', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create a new list item
    $listItem = $shoppingList->items()->create([
        'title' => 'My List Item',
        'price' => '10',
    ]);

    $component = Volt::test('shoppinglist', [ 'list' => $shoppingList ])
        ->call('removeListItem', $listItem->id);

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseMissing('list_items', [
        'id' => $listItem->id,
    ]);
});

test('ListItem can be marked as completed or in-active', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create a new list item
    $listItem = $shoppingList->items()->create([
        'title' => 'My List Item',
        'price' => '10',
        'is_active' => 1,
    ]);

    $component = Volt::test('shoppinglist', [ 'list' => $shoppingList ])
        ->call('toggleActive', $listItem);

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseHas('list_items', [
        'id' => $listItem->id,
        'is_active' => 0,
    ]);
});