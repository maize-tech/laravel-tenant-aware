<?php

namespace Maize\TenantAware\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maize\TenantAware\Support\Config;

abstract class ScopeMethod
{
    public function name(): string
    {
        return Str::of(class_basename($this))
            ->after('Scope')
            ->camel();
    }

    protected function resolveTenantForeignKeyName(Builder $builder): string
    {
        $model = $builder->getModel();

        if (method_exists($model, 'getQualifiedTenantKeyName')) {
            return $model->getQualifiedTenantKeyName();
        }

        return $model->qualifyColumn(
            Config::getTenantForeignKeyName()
        );
    }
}
