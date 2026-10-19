\# Cookbook



A small cookbook app built with Symfony as a trial task: enter, list and view recipes.



\## Features



\*\*Base task\*\*

\- Front page with a form to add a recipe (title, preparation time, ingredients, instructions)

\- Ingredients can be added and removed dynamically (amount, unit, name)

\- Recipe list as responsive cards: 1–4 per row depending on screen size, all cards in a row the same height

\- Recipe detail page with title, preparation time, ingredients, instructions and a link back

\- Success message after saving

\- Server-side validation for all fields



\*\*Bonus tasks\*\*

\- \*\*Bonus 1:\*\* multiply ingredient amounts on the recipe page, live, without a reload (Stimulus)

\- \*\*Bonus 2:\*\* the recipe list updates after saving without a page reload (Turbo Streams)

\- \*\*Bonus 3:\*\* optional image upload, shown on the card and the detail page



\## Tech stack



\- PHP 8.2+, Symfony 7.4 (LTS)

\- Doctrine ORM with SQLite

\- Twig, Symfony UX (Stimulus, Turbo), AssetMapper (no Node.js build step)

\- Bootstrap 5



\## Setup



Requirements: PHP 8.2+ (with `pdo\_sqlite`, `intl`, `fileinfo`), Composer, optionally the Symfony CLI.



```bash

git clone https://github.com/safagrechh/cookbook.git

cd cookbook

composer install

php bin/console doctrine:migrations:migrate --no-interaction

symfony server:start

```



Then open http://127.0.0.1:8000.

Without the Symfony CLI: `php -S 127.0.0.1:8000 -t public`



\## Project structure



| Path | Purpose |

|---|---|

| `src/Entity/Recipe.php`, `Ingredient.php` | Data model, validation rules |

| `src/Enum/Unit.php` | Units (grams, tablespoons, pieces) as a PHP enum |

| `src/Form/RecipeType.php`, `IngredientType.php` | Recipe form with embedded ingredient collection |

| `src/Controller/RecipeController.php` | Front page (form + list) and recipe page |

| `src/Service/RecipeImageUploader.php` | Stores uploaded images with safe, unique filenames |

| `assets/controllers/form-collection\_controller.js` | Adds/removes ingredient rows |

| `assets/controllers/ingredient-scaler\_controller.js` | Bonus 1: amount multiplier |

| `templates/recipe/created.stream.html.twig` | Bonus 2: Turbo Stream response after saving |

| `assets/styles/app.css` |	Custom styling on top of Bootstrap



\## Design decisions



\- \*\*SQLite\*\*, so the project runs without a database server. Switching to MySQL/PostgreSQL only means changing `DATABASE\_URL` and regenerating the migration.

\- \*\*Amount is an `int`, as specified.\*\* In practice, values like 0.5 tbsp would be useful, and switching to a decimal column would be a small change. Bonus 1 scales amounts for display only, shown with up to 2 decimals. The stored values never change.

\- \*\*Post/Redirect/Get after saving\*\*, so a page refresh doesn't submit the recipe twice.

\- \*\*Turbo Streams with a fallback (progressive enhancement).\*\* With JavaScript, only the list, the form and the message are updated. Without JavaScript, the app falls back to a normal redirect with a flash message.

\- \*\*Instructions are plain text with line breaks\*\* (`nl2br`, HTML is escaped). Markdown would be a possible extension; raw HTML input would need sanitizing first.

\- \*\*Images are stored in `public/uploads/recipes/`, not in the database.\*\* Filenames are slugged and made unique. The `Image` constraint checks the real file type and limits size to 2 MB.



\## Known limitations and possible next steps



\- No editing or deleting of recipes (not part of the task)

\- No pagination for the recipe list

