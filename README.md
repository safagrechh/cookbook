# Cookbook

A small cookbook app built with Symfony as a trial task: enter, list and view recipes.

## Features

**Base task**
- Front page with a form to add a recipe (title, preparation time, ingredients, instructions)
- Ingredients can be added and removed dynamically (amount, unit, name)
- Recipe list as responsive cards: 1–3 per row depending on screen size, all cards in a row the same height
- Recipe page with title, preparation time, ingredients, instructions and a link back to the front page
- Success message after saving
- Server-side validation for all fields

**Bonus tasks**
- **Bonus 1:** multiply ingredient amounts on the recipe page, live, without a reload (Stimulus)
- **Bonus 2:** the recipe list updates after saving without a page reload (Turbo Streams)
- **Bonus 3:** optional image upload, shown on the card and the recipe page

**UI/UX**
- Two-column front page on large screens: the form stays visible (sticky) while browsing the recipe library
- Recipe page with a large image header, the multiplier placed next to the ingredients, and instructions shown as numbered steps
- New ingredient rows are scrolled into view and focused automatically
- Consistent custom styling (CSS variables for colors, radii and shadows) on top of Bootstrap, responsive down to phone width

## Tech stack

- PHP 8.2+, Symfony 7.4 (LTS)
- Doctrine ORM with SQLite
- Twig, Symfony UX (Stimulus, Turbo), AssetMapper (no Node.js build step)
- Bootstrap 5

## Setup

Requirements: PHP 8.2+ (with `pdo_sqlite`, `intl`, `fileinfo`), Composer, optionally the Symfony CLI.

```bash
git clone https://github.com/safagrechh/cookbook.git
cd cookbook
composer install
php bin/console doctrine:migrations:migrate --no-interaction
symfony server:start
```

Then open http://127.0.0.1:8000.


## Project structure

| Path | Purpose |
|---|---|
| `src/Entity/Recipe.php`, `Ingredient.php` | Data model and validation rules |
| `src/Enum/Unit.php` | Units (grams, tablespoons, pieces) as a PHP backed enum |
| `src/Form/RecipeType.php`, `IngredientType.php` | Recipe form with an embedded ingredient collection |
| `src/Controller/RecipeController.php` | Front page (form + list) and recipe page |
| `src/Service/RecipeImageUploader.php` | Stores uploaded images with safe, unique filenames |
| `assets/controllers/form-collection_controller.js` | Adds and removes ingredient rows |
| `assets/controllers/scale_controller.js` | Bonus 1: amount multiplier |
| `templates/recipe/created.stream.html.twig` | Bonus 2: Turbo Stream response after saving |
| `assets/styles/app.css` | Custom styling on top of Bootstrap |

## Design decisions

- **SQLite**, so the project runs without a database server. Switching to MySQL/PostgreSQL only means changing `DATABASE_URL` and regenerating the migrations.
- **Amount is an `int`, as specified.** In practice, values like 0.5 tbsp would be useful; switching to a decimal column would be a small change. Bonus 1 scales amounts for display only, with up to 2 decimals. The stored values never change.
- **Server-side validation shows all errors at once.** Browser validation is turned off (`novalidate`) so every invalid field is marked in one go, with a short summary above the form.
- **Post/Redirect/Get after saving**, so a page refresh doesn't submit the recipe twice.
- **Turbo Streams with a fallback (progressive enhancement).** With JavaScript, only the list, the form and the message are updated. Without JavaScript, the app falls back to a normal redirect with a flash message.
- **Instructions are plain text.** On the recipe page, each line is shown as a numbered step. Output is escaped by Twig, so user input can't inject scripts (XSS). Markdown would be a possible extension; raw HTML input would need sanitizing first (e.g. with `symfony/html-sanitizer`).
- **Images are stored in `public/uploads/recipes/`, not in the database.** Only the filename is saved. Filenames are slugged and made unique, and the extension is derived from the file content. The `Image` constraint checks that the file is a real image and limits it to 2 MB.

## Known limitations and possible next steps

- Recipes can't be edited or deleted yet (not part of the task)
- No pagination for the recipe list
