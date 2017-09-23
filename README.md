## Installation
```
composer require krossroad/laravel-union-paginator
```

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
