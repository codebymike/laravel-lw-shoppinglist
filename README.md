# Developer Coding Challenge

## Shopping List App

### Task

As a healthcare company we have a keen eye on healthy eating and it’s been suggested by an employee that we have an easy way to keep track of what we need, what needs to be purchased and keep spending within the budget constraints. Below, are a number of stories which will achieve this objective.

### Rules

-   Agree the number of hours you can commit to (up to 6) and select the number of stories you feel like you can achieve within this time.
-   Commit the code to a public repository.
-   Don’t worry about using our tech stack, use a stack you’re comfortable with.
-   Please email a link to your repository by the deadline provided.

### Stories (Shortened)

1. View a list of items on a shopping list
2. Add items to the shopping list
3. Remove stuff from the shopping list
4. When I’ve bought something from my list I want to be able to cross it off the list
5. Persist the data so I can view the list if I move away from the page
6. I want to be able to reorder items on my list
7. Total up the prices
8. Put a spending limit in place, alert me if I go over the limit
9. I want to share my shopping list via email
10. User and password protect

### Task Diary / Thought Log

Goal of this section is to outline my thought process and justify any decisions made. Also to log any interesting console cmds used.

#### Initial Thoughts

-   Happy to commit the full 6 hours, seems reasonable to touch and test each of the 10 story points within that time-frame
-   Initial tasks are frontend UI focused; however the requirements for persistence, emails and auth in the later tasks heavily suggest the need for a backend service.
-   Don't want to over-engineer, focus on simplicity and code cleanliness.
-   Few options for technologies. Decided upon Laravel for backend (M+C), and Livewire for frontend (V). Primarily due to experience and knowledge that Laravel would deliver what was required easily. Less experienced with Livewire, but after reading documentation and its close-integration with Laravel, felt like a natural fit and an excuse to learn something a little newer.
-   Considered options, decided upon using SQLite for DB. Light-weight, simple, scalable. Perfect for a project of this size.
-   Following the Zero one infinity (ZOI) rule, and making some assumptions, a user is going to want more than one list at once. So I'm going start with the ability to model 'user -> many lists -> many items'.
-   In the interest of being data-driven, designed the core data-models first:

#### Data Models

(TODO: turn into an image)
User
ID
(basic ident. & auth params)

List
ID
UserID (FK)
title
is_active
(timestamps)

Item
ID
ListID (FK)
title
qty
is_active
(timestamps)

-   These models will be updated later to cater for future stories, but this will accomplish the initial functionality.

#### Project Initialisation and "Hello World" Tests

-   running PHP v8.4 & NodeJS v20.11.0
-   Laravel/Breeze is a basic auth 'starter kit' (incl. Livewire + Tailwind), which will be useful later

```
$ composer create-project laravel/laravel .
$ npm i
$ composer require laravel/breeze --dev
$ php artisan breeze:install
$ herd open
```

#### First Models + Migrations

-   wanted to call the model a more generic 'List', but it's reserved by PHP

```
$ php artisan make:model ShoppingList -m
$ php artisan make:model ListItem -m
$ php artisan migrate
$ php artisan db:seed
```

-   This will seed you a test user profile (test@example.com / password)
-   Considered pausing here to make model factories & seeders, decided to revist later once ready for more comprehensive testing

#### First Frontend component (using Livewire / Volt)

```
$ php artisan make:volt
```

-   Created component functionality for adding a shopping list, tying it to the user - then listing out their shopping lists
-   Going to use basic routing for simplity

```
$ php artisan make:controller ShoppingListController --resource
```

-   Most of these resource methods will be removed once I know they're not needed
-   Added routing / controller::show / template

#### (Stories 1 + 2) Show List of Items & Ability to Add

-   Added ShoppingList volt component for listing + added items to the current list

#### (Story 3) Remove Item from List

-   Updated ShoppingList component with remove functionality
-   TODO: Add confirmation check?

#### (Story 4) Cross Item off List

-   Updated ShoppingList component with ability to change item 'is_active' flag, and to display that in the list
-   TODO: Fix button placements

#### (Story 5) Persist List

-   Already accomplished as part of initial list set up

#### (Story 6) Re-order Items on List

-   There's a number of ways to accomplish this: numerical order updates, up/down buttons on each item. However standard UX expectations would strongly push towards a 'drag & drop' interface.
-   Accessibility benefits: intuitive, easy to perform on mobile devices. Costs: hard for someone with poor vision, accuracy or motor-skills
-   Ideally would offer a less interactive fall-back option.
-   Livewire has an officially supported 'Sortable' plugin which should make the implementation of this functionality trivial
-   Added the ext. hosted JS file script tag only the required template, avoiding including it in unnecessary requests. Also added the 'defer' tag to prevent it blocking page load, and instructing it to only be run once the page has finished loading.
-   Created a migration to add 'order' integer field to the ListItem model
-   Make decision to handle item sorting by: defaulting each item's 'order' to 0, an 'unsorted' list will then default to being rendered in 'created_by' order
    -   When a user action is taken to sort a list, only then will each item on that list be given the new order - which will be updated on each subsequence re-order
    -   This will avoid uncessary order increament calculations when adding a new item to a list
    -   Assumption: adding an item to a list will occur more frequently than ordering a list.

```
$ php artisan make:migration add_order_to_list_items_table
$ php artisan migrate
```

-   Added 'sortedItems' method to ListItem model to specify the correct order precedence for loading of a list's items
-   Wrote the function for updating the list items once the order has been updated
-   Added better styling to make the dragged element clearer
-   TODO: Make this visually cleaner

#### (Story 7) Total Prices

-   Added price field to ListItem model
-   Assumption: Currency is in GDP

```
$ php artisan make:migration add_price_to_list_items_table
$ php artisan migrate
```

-   Added price state to ListItem component model + view
-   Validate price using a mix of HTML5 input validation & Laravels form validation rules
-   Assumption: total price should include all items, active and inactive
