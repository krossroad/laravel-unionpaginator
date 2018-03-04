
[![Package Control](https://img.shields.io/packagecontrol/dt/GitGutter.svg?style=social)](https://packagist.org/packages/krossroad/laravel-union-paginator)

## Installation
```
composer require 'krossroad/laravel-union-paginator:5.5'
```
For Laravel v5.4 use `'krossroad/laravel-union-paginator:5.4'`.

## Usage

Just use `UnionPaginatorTrait` in your model and you are good to go.

```php
#Example Model
use Krossroad\UnionPaginator\UnionPaginatorTrait;

class User extends Model
{
    use UnionPaginatorTrait;
    ...
}

$first = User::whereNull('first_name');

$pagination = User::whereNull('last_name')
    ->union($first)
    ->unionPaginate($perPage, $columns, $pageName = 'page', $page);
```
