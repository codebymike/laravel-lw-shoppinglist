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

N.B - These models will be updated later to cater for future stories, but this will accomplish the initial functionality.

#### Project Initialisation and "Hello World" Tests

-   running PHP v8.4 & NodeJS v20.11.0
-   Laravel/Breeze is a basic auth 'stater kit' (incl. Livewire + Tailwind), which will be useful later

```
$ composer create-project laravel/laravel .
$ npm i
$ composer require laravel/breeze --dev
$ php artisan breeze:install
$ herd open
```

#### First Models + Migrations

```
$ php artisan make:model ShoppingList -m
$ php artisan make:model ListItem -m
```
