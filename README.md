# Kirby List Methods

Kirby 3 plugin providing `toList()` methods for users, pages and files collections. It creates a comma-separated list of all collection items with an optional conjunction for the last item.

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

## Usage

There are different options to create lists from users, pages or files collections:

### Comma-separated list using the primary field

Creates a list separated all items with a comma:

```php
// using the username
$users->toList();

// using the page title
$pages->toList();

// using the filename
$files->toList();
```

### Comma-separated list using a custom field or method

Creates a list separated all items with a comma:

```php
// using the custom method `nickname`
$users->toList('nickname');

// using the field category
$pages->toList('category');
```

### Comma-separated list using a conjunction

Creates a list separated all items with a comma but the last which is connected with a cunjunction:

```php
// creates, a, list, with, commas and conjunction
$pages->toList('title', true)

// creates, a, list, with, commas & conjunction
$pages->toList('title', '&')
```

The default conjunction `and` is provided in English or German depending on your language settings.

### Comma-separated list with dynamic links

Creates a list linking to a custom destination:

```php
// link everything to the same URL
$pages->toList('title', true, 'https://example.com');

// link all pages to their own URL
$pages->toList('title', true, '{{page.url}}');

// link all pages to a custom URL
$pages->toList('title', true, 'my-custom-path/{{page.category}}')
```

You can use Kirby's template syntax with [query language](https://getkirby.com/docs/guide/blueprints/query-language) to fetch any information from the current context, e. g. the current `$user`, `$page` or `$file` object. The `$kirby` and `$site` objects are also available.

### Helper

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

## License

MIT

## Credits

[hana+nils · Büro für Gestaltung](https://hananils.de)
