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

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ])
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

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ]);

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

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ])
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

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ])
        ->call('toggleActive', $listItem);

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseHas('list_items', [
        'id' => $listItem->id,
        'is_active' => 0,
    ]);
});

test('ListItems can be re-ordered', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create new list items
    $listItem1 = $shoppingList->items()->create([
        'title' => 'Item 1',
        'price' => '10',
        'order' => 1,
    ]);

    $listItem2 = $shoppingList->items()->create([
        'title' => 'Item 2',
        'price' => '20',
        'order' => 2,
    ]);

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ])
        ->call('updateListOrder', [
            ['value' => $listItem2->id, 'order' => 1],
            ['value' => $listItem1->id, 'order' => 2],
        ]);

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertDatabaseHas('list_items', [
        'id' => $listItem1->id,
        'order' => 2,
    ]);

    $this->assertDatabaseHas('list_items', [
        'id' => $listItem2->id,
        'order' => 1,
    ]);
});

test('ShoppingList will calculate items total price', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
    ]);

    // Create new list items
    $listItem1 = $shoppingList->items()->create([
        'title' => 'Item 1',
        'price' => '10.10',
    ]);

    $listItem2 = $shoppingList->items()->create([
        'title' => 'Item 2',
        'price' => '20.20',
    ]);

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ]);

    $component
        ->assertSee('Shopping List Total: £30.30');
});

test('ShoppingList can have price limit', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
        'price_limit' => 50.00,
    ]);

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ]);

    $component
        ->assertSet('price_limit', 50.00);
});

test('ShoppingList alerts customer when over the limit', function () {
    $this->actingAs($user = User::factory()->create());

    // Create a new shopping list first
    $shoppingList = ShoppingList::factory()->create([
        'user_id' => $user->id,
        'price_limit' => 15.00,
    ]);

    // Create new list items
    $listItem1 = $shoppingList->items()->create([
        'title' => 'Item 1',
        'price' => '10.10',
    ]);

    $listItem2 = $shoppingList->items()->create([
        'title' => 'Item 2',
        'price' => '20.20',
    ]);

    $component = Volt::test('shopping_list.edit', [ 'list' => $shoppingList ]);

    $component
        ->assertSee('- Over Limit!');
});