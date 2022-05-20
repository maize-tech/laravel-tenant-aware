<?php

namespace Maize\TenantAware;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Maize\TenantAware\Scopes\ScopeMethod;
use Maize\TenantAware\Support\Config;

class TenantAwareScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        Config::getScopeApply()($builder);
    }

    public function extend(Builder $builder): void
    {
        Config::getScopeMethods()->each(
            fn (ScopeMethod $method, string $name) => $builder->macro($name, $method)
        );
    }
}
