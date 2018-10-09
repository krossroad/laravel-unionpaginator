## Laravel Union Paginator

### Installation

```
composer require 'krossroad/laravel-union-paginator:5.5'
```

For Laravel v5.2 to v5.4
```
composer require 'krossroad/laravel-union-paginator:5.4'
```

### Usage

> Just use `UnionPaginatorTrait` in your model and you are good to go.

#### Example Model

```php
<?php
/**
 * @filename {project}/App/Models/User.php
 */

namespace \App\Models\User;

use Krossroad\UnionPaginator\UnionPaginatorTrait;

class User extends Model
{
    use UnionPaginatorTrait;
    ...
}

```

#### Example usage somewhere in application code

> `->unionPaginate()` --> returns \Illuminate\Pagination\LengthAwarePaginator instance

```php
$first = User::whereNull('first_name');

$pagination = User::whereNull('last_name')
    ->union($first)
    ->unionPaginate(
        $perPage,
        $columns,
        $pageName = 'page',
        $page
    );
    
```

If you find any bug, issue or have queries. Please [create a new issue](https://github.com/krossroad/laravel-unionpaginator/issues/new) 
