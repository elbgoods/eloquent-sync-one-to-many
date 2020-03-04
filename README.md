# Laravel Sync OneToMany

This package provides the sync function for one to many relations similar to the sync method from BelongsToMany.

## Installation

You can install the package via composer:

```bash
composer require elbgoods/laravel-sync-one-to-many
```

## Usage

Sync using ids
``` php
$user->tasks()->sync([1, 2, 4]);
```

Sync using ids and additional attributes
``` php
$user->tasks()->sync([
    1 => ['status' => 'wip', 'priority' => 1],
    4 => ['status' => 'finished', 'priority' => 3],
]);
```

Sync without detaching
``` php
$user->tasks()->syncWithoutDetaching([1, 2, 4]);

// or

$user->tasks()->sync([1, 2, 4], ['detaching' => false]);
```
Sync and set additional attributes to detached 
``` php
$user->tasks()->sync(
    [1, 2, 4],
    'set_after_detach' => [
        'status' => 'open',
        'priority' => 0,
    ],
);
```

Result is the same as the result of the sync method of `BelongsToMany`, an array with attach, detached and updated rows.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

Please see [SECURITY](SECURITY.md) for details.

## Credits

- [Niclas Schirrmeister](https://github.com/eisfeuer)
- [Tom Witkowski](https://github.com/gummibeer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment we would highly appreciate you buying or planting the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to [plant trees](https://www.bbc.co.uk/news/science-environment-48870920). If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at https://offset.earth/treeware

Read more about Treeware at https://treeware.earth

[![We offset our carbon footprint via Offset Earth](https://toolkit.offset.earth/carbonpositiveworkforce/badge/5e186e68516eb60018c5172b?black=true&landscape=true)](https://offset.earth/treeware)

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
