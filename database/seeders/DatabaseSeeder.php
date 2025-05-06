<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $shoppingList = $user->shoppingLists()->create([
            'title' => 'Weekly Shop',
            'is_active' => true,
            'price_limit' => 25.00,
        ]);

        $shoppingList->items()->create([
            'title' => 'Milk',
            'price' => 1.50,
        ],[
            'title' => 'Eggs',
            'price' => 3.00,
        ],[
            'title' => 'Beans',
            'price' => 4.50,
        ],[
            'title' => 'Celery',
            'price' => 1.25,
        ],[
            'title' => 'Biscuits',
            'price' => 3.49,
        ]);   
    }
}
