<?php

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
