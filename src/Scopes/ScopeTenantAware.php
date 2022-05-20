<?php

namespace Maize\TenantAware\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Maize\TenantAware\Support\Config;

class ScopeTenantAware extends ScopeMethod
{
    public function __invoke(Builder $builder): Builder
    {
        app(ScopeTenantWhere::class)(
            $builder,
            Config::getTenantCurrentOrLandlordAction()()
        );

        if (Config::getTenantOnlyCurrentAction()($builder)) {
            return $builder;
        }

        return app(ScopeOrTenantWhere::class)(
            $builder,
            Config::getTenantLandlordAction()()
        );
    }
}
