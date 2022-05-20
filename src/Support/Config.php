<?php

namespace Maize\TenantAware\Support;

use Illuminate\Support\Collection;
use Maize\TenantAware\Exceptions\InvalidConfig;
use Maize\TenantAware\Scopes\ScopeMethod;

class Config
{
    public static function getTenantModelName(): string
    {
        return config('tenant-aware.tenant.model')
            ?? throw InvalidConfig::couldNotDetermineTenantModelName();
    }

    public static function getTenantForeignKeyName(): string
    {
        return config('tenant-aware.tenant.foreign_key_name', 'tenant_id');
    }

    public static function getTenantCurrentAction(): callable
    {
        $action = config('tenant-aware.tenant.actions.current')
            ?? throw InvalidConfig::couldNotDetermineTenantCurrentActionName();

        return app($action);
    }

    public static function getTenantLandlordAction(): callable
    {
        $action = config('tenant-aware.tenant.actions.landlord')
            ?? throw InvalidConfig::couldNotDetermineTenantLandlordActionName();

        return app($action);
    }

    public static function getTenantCurrentOrLandlordAction(): callable
    {
        $action = config('tenant-aware.tenant.actions.current_or_landlord')
            ?? throw InvalidConfig::couldNotDetermineTenantCurrentOrLandlordActionName();

        return app($action);
    }

    public static function getTenantOnlyCurrentAction(): callable
    {
        $action = config('tenant-aware.tenant.actions.only_current')
            ?? throw InvalidConfig::couldNotDetermineTenantOnlyCurrentActionName();

        return app($action);
    }

    public static function getGlobalModelsName(): array
    {
        return config('tenant-aware.models.global');
    }

    public static function getModelCreatingListener(): callable
    {
        $action = config('tenant-aware.models.listen.creating');

        if (is_null($action)) {
            return fn ($model) => $model;
        }

        return app($action);
    }

    public static function getScopeApply(): callable
    {
        $method = config('tenant-aware.scope.apply')
            ?? throw InvalidConfig::couldNotDetermineScopeApplyName();

        return app($method);
    }

    public static function getScopeMethods(): Collection
    {
        return collect(config('tenant-aware.scope.methods'))
            ->map(fn (string $method) => app($method))
            ->mapWithKeys(fn (ScopeMethod $method) => [
                $method->name() => $method,
            ]);
    }
}
