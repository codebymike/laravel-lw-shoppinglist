# Developer Coding Challenge

## Shopping List App

### Task

As a healthcare company we have a keen eye on healthy eating and it’s been suggested by an employee that we have an easy way to keep track of what we need, what needs to be purchased and keep spending within the budget constraints. Below are a number of stories which will achieve this objective.

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
-   Following the Zero one infinity (ZOI) rule, and making some assumptions, a user is going to want more than one list at once. So I'm going start with the relationship model 'user -> many lists -> many items'.
-   In the interest of being data-driven, designed the core data-models first:

#### Data Models

-   User
    -   ID
    -   (basic ident. & auth params)
    -   Has Many ->
-   ShoppingList
    -   ID
    -   UserID (FK)
    -   title
    -   is_active
    -   (timestamps)
    -   Has Many ->
-   ListItem

    -   ID
    -   ListID (FK)
    -   title
    -   is_active
    -   (timestamps)

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
-   Considered pausing here to make model factories & seeders, decided to revisit later once ready for more comprehensive testing

#### First Frontend component (using Livewire / Volt)

```
$ php artisan make:volt
```

-   Created component functionality for adding a shopping list, tying it to the user - then listing out their shopping lists
-   Going to use basic routing for simplicity

```
$ php artisan make:controller ShoppingListController --resource
```

-   Most of these resource methods will be removed once I know they're not needed
-   Added routing / controller::show / template

#### (Stories 1 + 2) Show List of Items & Ability to Add

-   Added ShoppingList volt component for listing + added items to the current list

#### (Story 3) Remove Item from List

-   Updated ShoppingList component with remove functionality

#### (Story 4) Cross Item off List

-   Updated ShoppingList component with ability to change item 'is_active' flag, and to display that in the list

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
    -   This will avoid unnecessary order increment calculations when adding a new item to a list
    -   Assumption: adding an item to a list will occur more frequently than ordering a list.

```
$ php artisan make:migration add_order_to_list_items_table
$ php artisan migrate
```

-   Added 'sortedItems' method to ListItem model to specify the correct order precedence for loading of a list's items
-   Wrote the function for updating the list items once the order has been updated
-   Added better styling to make the dragged element clearer

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

#### Pit-stop for UX, UI & Accessibility Improvements

-   Improved the UI for the ShoppingList component, interface was cluttered with elements too close together
-   Added better labels + aria-labels to all interactive elements
-   Improved 'drag & drop' UI icon, added border around element to better signal 'drag-ability'
-   Added 'are you sure?' confirmation to item-remove button
-   Improved active/crossed-off ListItem UI

#### (Story 8) Spending Limit + Alert

-   The 'limit' value should live on the ShoppingList model and either be nullable, or ignored if the value is 0. The UI for the Spending Limit feels more useful on the ItemsList page
-   There are numerous ways exceeding this limit could be communicated to the user, going to keep it simple and use colours.

```
$ php artisan make:migration add_limit_shopping_lists_table
$ php artisan migrate
```

-   Added Limit to the component state, added the updatePriceLimit functionality and UI flags for displaying the colour-based (green/red) feedback.

### Final Thoughts + Notes

-   Don't feel like I used LiveWire/Volt in the optimal way, probably better when utilising full page components w/ routing.
-   The final UI for the ShoppingList component could definitely be made more accessible, with clearer labeling, descriptions and aria-attributing.
-   In the Challenge PDF there is a story duplication error in story#8.
-   If I had more time:
    -   Implement Story 9. Estimate would have taken approx 1 more hour, incl: 'public' shopping list view, laravel emails+commands, templates & testing.
    -   I would break down the main ShoppingList component to smaller subcomponents (initial attempts broke drag-UI functionality).
    -   Add more tests to cover errors, input-boundaries.
    -   Better accessibility options: graceful no-javascript fallbacks, better error messaging, clearer UI etc.
    -   ShoppingList item's title length has no length limit, didn't want to add a short, arbitrary limit or truncation - would rework UI to allow cleaner overflow.
    -   Slightly better dark/light mode support.

### Build + Run Instructions

-   Assuming you're running php 8.4x and node 20.x

```
$ git clone https://github.com/codebymike/laravel-lw-shoppinglist.git
$ cd laravel-lw-shoppinglist/
$ composer install
$ npm install
$ cp .env.example .env
$ php artisan key:generate
```

-   Update the .env APP_URL value to what it will be when running locally e.g using Herd http://shoppinglist.test .

```
$ php artisan migrate
$ php artisan db:seed
```

-   Seeding will generate a sample user, shopping list and items. User access details are: test@example.com / password.
-   To run tests:

```
$ php artisan test
```
