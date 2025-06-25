![Kirby List Methods](.github/title.png)

**List Methods** is a plugin for [Kirby](https://getkirby.com) providing methods to generate comma-separated list from collections like pages, users or files. It allows for custom field selection for the list value, optional conjunctions for the last item (e. g. "and") and custom links for each item that can be defined using Kirby's query language (e. g. `{{page.url}}`). It also provides specific methods to list numeric values like years, shortening ranges for better readability.

> [!NOTE]
> Please check out the online documentation at [kirby.hananils.de/plugins/list-methods](https://kirby.hananils.de/plugins/list-methods) for further information.

## Installation

### Download

Download and copy this repository to `/site/plugins/list-methods`.

### Git submodule

```
git submodule add https://github.com/hananils/kirby-list-methods.git site/plugins/list-methods
```

### Composer

```
composer require hananils/kirby-list-methods
```

# Pages, Users and File Methods

There are different options to create lists from users, pages or files collections:

## Comma-separated list using the primary field

Creates a list separated all items with a comma:

```php
// using the username
$users->toList();

// using the page title
$pages->toList();

// using the filename
$files->toList();
```

Creates a list of years:

```php
// numeric list, returns "2010–2012, 2022" for "2010, 2011, 2012, 2022"
$page->years()->toNumericList();

// numeric list with "since", return "since 2020" for "2020, 2021, 2022"
$page->years()->toNumericList(true);
```

## Comma-separated list using a custom field or method

Creates a list separated all items with a comma:

```php
// using the custom method `nickname`
$users->toList('nickname');

// using the field category
$pages->toList('category');

// with numeric values
$pages->toNumericList('date');
```

## Comma-separated list using a conjunction

Creates a list separated all items with a comma but the last which is connected with a cunjunction:

```php
// creates, a, list, with, commas and conjunction
$pages->toList('title', true)

// creates, a, list, with, commas & conjunction
$pages->toList('title', '&')
```

The default conjunction `and` is provided in English or German depending on your language settings.

## Comma-separated list with dynamic links

Creates a list linking to a custom destination:

```php
// link everything to the same URL
$pages->toList('title', true, 'https://example.com');

// link all pages to their own URL
$pages->toList('title', true, '{{page.url}}');

// link all pages to a custom URL
$pages->toList('title', true, 'my-custom-path/{{page.category}}');

// link all pages to a custom URL with numeric values
$pages->toNumericList('date', true, 'my-year-overview/{{page.date.toDate('Y')}}');

```

You can use Kirby's template syntax with [query language](https://getkirby.com/docs/guide/blueprints/query-language) to fetch any information from the current context, e. g. the current `$user`, `$page` or `$file` object. The `$kirby` and `$site` objects are also available.

# Content methods

When dealing with a single page or user, there are methods to generate lists from content field:

```php
// Given the fields name and job, creates "Jane Doe, astrophysicist"
echo $user->asList(['name', 'job']);

// Given the fields start and end, creates "2020–2023"
echo $page->asNumericList(['start', 'end']);
```

Both methods, `asList` and `asNumericList`, support setting a custom conjunction via a secondary attribute:

```php
//  Given the fields name and job, creates "Jane Doe: astrophysicist"
echo $page->asList(['name', 'job'], ': ');
```

# Collection methods

The plugin also features a general, more simple collection method which is a shortcut the `naturalList()` helper and only allows for a custom conjunction:

```php
// Create a Choices collection for instance, see https://github.com/hananils/kirby-choices
$choices = $page->categories()->toChoices();

// Convert all choices to a comma-separated list
echo $choices->toList();

// Convert all choices to a comma-separated list with the default conjunction
echo $choices->toList(true);

// Convert all choices to a comma-separated list with a custom conjunction
echo $choices->toList('&');
```

# Helper

If you'd like to create a list outside of the Kirby objects, from an array for instance, you can use the `naturalList()` helper. It accepts a flat array and a conjunction, there is no custom key selection or template syntax support:

```php
$data = ['this', 'that'];

// this, that
naturalList($data);

// this and that
naturalList($data, true);

// this & that
naturalList($data, '&');
```

If you are handling numeric values, you can use the `numericList()` helper:

```php
$data = [2019, 2020, 2021, 2022];

// 2019-2022
numericList($data);

// since 2022
numericList($data, true);
```

# License

This plugin is provided freely under the [MIT license](LICENSE.md) by [hana+nils · Büro für Gestaltung](https://hananils.de). We create visual designs for digital and analog media.
