<?php

namespace Maize\TenantAware\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScopeTenantWhere extends ScopeMethod
{
    public function __invoke(Builder $builder, ?Model $tenant): Builder
    {
        return $builder->where(
            $this->resolveTenantForeignKeyName($builder),
            $tenant?->getKey()
        );
    }
}
