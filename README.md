# A trait to make Eloquent models archivable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nowendwell/laravel-archivable.svg?style=flat-square)](https://packagist.org/packages/nowendwell/laravel-archivable)
[![Total Downloads](https://img.shields.io/packagist/dt/nowendwell/laravel-archivable.svg?style=flat-square)](https://packagist.org/packages/nowendwell/laravel-archivable)

This package contains a trait to make Eloquent models archivable. This works in a similar way to the SoftDelete trait that Laravel ships with.

Once you have applied to the trait to a model you can do the following:

```php
// to archive a User
$user = User::find(1);
$user->archive();

// to unarchive a User
$user = User::withArchived()->find(1);
$user->unarchive();
```

## Installation

You can install the package via composer:

```bash
composer require nowendwell/laravel-archivable
```

## Making a model archivable

To make model archivable add the `Nowendwell\LaravelArchivable\Archivable` trait to the model you wish to archive  

```php
use Illuminate\Database\Eloquent\Model;
use Nowendwell\LaravelArchivable\Archivable;

class User extends Model
{
    use Archivable;
}
```

## Usage

This traits adds a Global Query Scope to exclude any models that have a value in the `archived_at` column.

### Archiving
``` php
// to archive a User
$user = User::find(1);
$user->archive();
```

### Unarchiving
``` php
// to unarchive a User
$user = User::withArchived()->find(1);
$user->unarchive();
```

### Checking Archive Status
``` php
// to unarchive a User
$user = User::withArchived()->find(1);
var_dump( $user->archived() ); // bool true/false
```

### Query Scopes
```php
User::withArchived()->get(); // returns all users
User::withOutArchived()->get(); // returns users that are not archived, same results as User::all()
User::onlyArchived()->get() // returns only users that have a value in the archived_at column
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email nowendwell@gmail.com instead of using the issue tracker.

## Credits

- [Ben Miller](https://github.com/nowendwell)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
