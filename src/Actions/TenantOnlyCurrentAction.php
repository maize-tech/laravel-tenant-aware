<?php

namespace Maize\TenantAware\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Maize\TenantAware\Support\Config;

class TenantOnlyCurrentAction
{
    public function __invoke(Model|Builder $builder): bool
    {
        if ($this->isInstanceofGlobalModels($builder)) {
            return $this->isRequestOnlyCurrentTenant();
        }

        return true;
    }

    protected function isRequestOnlyCurrentTenant(): bool
    {
        return request()->has('tenant.only_current');
    }

    protected function isInstanceofGlobalModels(Model|Builder $builder): bool
    {
        return $this->instanceofTypes(
            $this->getModelFromBuilder($builder),
            Config::getGlobalModelsName()
        );
    }

    protected function instanceofTypes(mixed $value, array|string|object $types): bool
    {
        foreach (Arr::wrap($types) as $type) {
            if ($value instanceof $type) {
                return true;
            }
        }

        return false;
    }

    protected function getModelFromBuilder(Builder|Model $builder): Model
    {
        if ($builder instanceof Model) {
            return $builder;
        }

        return $builder->getModel();
    }
}
