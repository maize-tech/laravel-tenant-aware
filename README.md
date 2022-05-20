<p align="center"><img src="/art/socialcard.png" alt="Social Card of Laravel Tenant Aware"></p>

# Laravel Tenant Aware

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-tenant-aware.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-tenant-aware)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-tenant-aware/run-tests?label=tests)](https://github.com/maize-tech/laravel-tenant-aware/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/maize-tech/laravel-tenant-aware/Check%20&%20fix%20styling?label=code%20style)](https://github.com/maize-tech/laravel-tenant-aware/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-tenant-aware.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-tenant-aware)

Easily integrate single-database multi tenant features into your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-tenant-aware
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag="tenant-aware-config"
```

This is the contents of the published config file:

```php
return [

    'tenant' => [

        /*
        |--------------------------------------------------------------------------
        | Tenant Model
        |--------------------------------------------------------------------------
        |
        | Here you may specify the fully qualified class name of the tenant model.
        |
        */

        'model' => null,

        /*
        |--------------------------------------------------------------------------
        | Tenant key name
        |--------------------------------------------------------------------------
        |
        | Here you may specify the column name for the tenant identifier.
        | All models using the multi-tenant feature should include this field
        | in their migration.
        |
        */

        'foreign_key_name' => 'tenant_id',

        /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        |
        | Here you may specify the list of actions used to retrieve che current
        | and landlord tenants.
        |
        */

        'actions' => [
            'current' => Maize\TenantAware\Actions\TenantCurrentAction::class,
            'landlord' => Maize\TenantAware\Actions\TenantLandlordAction::class,
            'current_or_landlord' => Maize\TenantAware\Actions\TenantCurrentOrLandlordAction::class,
            'only_current' => Maize\TenantAware\Actions\TenantOnlyCurrentAction::class,
        ],

    ],

    'models' => [
        /*
        |--------------------------------------------------------------------------
        | Global Models
        |--------------------------------------------------------------------------
        |
        | Here you may specify the full list of global models who should return
        | all entities, including the ones related to the landlord tenant.
        |
        */

        'global' => [
            // App\Models\Article::class,
        ],

        'listen' => [

            /*
            |--------------------------------------------------------------------------
            | Set tenant key
            |--------------------------------------------------------------------------
            |
            | Here you may specify the action invoked when creating a multi-tenant
            | model entity.
            | By default, the action sets the tenant field to the current tenant key.
            |
            */

            'creating' => Maize\TenantAware\Listeners\SetTenantKey::class,
        ],
    ],

    'scope' => [

        /*
        |--------------------------------------------------------------------------
        | Scope apply
        |--------------------------------------------------------------------------
        |
        | Here you may override the default scope method applied to all models
        | who belong to a tenant.
        | The default class adds a where constraint to exclude entities not related
        | to the current user's tenant or to the landlord tenant.
        |
        */

        'apply' => Maize\TenantAware\Scopes\ScopeTenantAware::class,

        /*
        |--------------------------------------------------------------------------
        | Scope Methods
        |--------------------------------------------------------------------------
        |
        | Here you may override the default scope methods used to add the
        | where constraints to all models who belong to a tenant.
        |
        */

        'methods' => [
            Maize\TenantAware\Scopes\ScopeOrTenantWhere::class,
            Maize\TenantAware\Scopes\ScopeTenantWhere::class,
            Maize\TenantAware\Scopes\ScopeTenantAware::class,
        ],
    ],
];
```

## Usage

### Configuration

Before getting started, make sure to fill in the `model` and `foreign_key_name` attributes under `config/tenant-aware.php`.

Also, don't forget to define your own `TenantCurrentAction` and `TenantLandlordAction` actions in order to retrieve both the current and landlord tenants.

For example, if you're using Spatie's [laravel-multitenancy](https://github.com/spatie/laravel-multitenancy) package your custom actions could look like this:

``` php
<?php

namespace Support\TenantAware;

use Spatie\Multitenancy\Models\Tenant;

class TenantCurrentAction
{
    public function __invoke(): ?Model
    {
        return Tenant::current();
    }
}
```

``` php
<?php

namespace Support\TenantAware;

use Maize\TenantAware\Support\Config;
use Spatie\Multitenancy\Models\Tenant;

class TenantLandlordAction
{
    public function __invoke(): ?Model
    {
        $tenantModel = Config::getTenantModelName();
        $landlordCode = 'landlord'; // get landlord tenant from your config, env, or wherever you defined it

        return app($tenantModel)::query()
            ->firstWhere('code', $landlordCode);
    }
}
```

### Basic

To use the package, add the `Maize\TenantAware\BelongsToTenant` trait to the models where you want to use the multi-tenant features.

Remember all your multi-tenant models should have the tenant field specified in `foreign_key_name` attribute under `config/tenant-aware.php`.

``` php
<?php

namespace App\Models;

use Maize\TenantAware\BelongsToTenant;

class User extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'fist_name',
        'last_name',
        'email',
        'tenant_id',
    ];
}
```

Once done, when querying any of your multi-tenant models you will only retrieve entities under your same tenant.

Let's say, for example, we have this list of users in our database, and the currently authenticated user is part of the tenant `2`:

| id  | email              | tenant_id | created_at | updated_at |
|-----|--------------------|-----------|------------|------------|
| 1   | user.a@example.com | 2         | //         | //         |
| 2   | user.b@example.com | 2         | //         | //         |
| 3   | user.c@example.com | 2         | //         | //         |
| 4   | user.d@example.com | 3         | //         | //         |

When querying the User model, you would only get the first three users:

``` php
use App\Models\User;

User::all()->modelKeys(); // returns [1, 2, 3]
```

### Querying global entities

Have a model who should handle both global and tenant related entities? No worries!

All you have to do is add the model class name to the `models.global` attribute under `config/tenant-aware.php`.
Also, make sure your landlord tenant is returned on your custom `TenantLandlordAction` action class!

``` php
'models' => [
    'global' => [
        App\Models\Course::class,
    ],
],
```

Let's say you have an e-learning multi-tenant platform with both global courses and custom courses for each tenant.

| id  | name                    | tenant_id | created_at | updated_at |
|-----|-------------------------|-----------|------------|------------|
| 1   | Awesome global course A | 1         | //         | //         |
| 2   | Awesome custom course B | 2         | //         | //         |
| 3   | Awesome custom course C | 2         | //         | //         |
| 4   | Awesome custom course D | 3         | //         | //         |

That's what you would get when querying the Course model for a user under tenant `2`:

``` php
use App\Models\Course;

Course::all()->modelKeys(); // returns [1, 2, 3]
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Enrico De Lazzari](https://github.com/enricodelazzari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
