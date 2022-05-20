<?php

namespace Maize\TenantAware\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScopeOrTenantWhere extends ScopeMethod
{
    public function __invoke(Builder $builder, ?Model $tenant): builder
    {
        return $builder->orWhere(
            $this->resolveTenantForeignKeyName($builder),
            $tenant?->getKey()
        );
    }
}
