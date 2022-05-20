<?php

namespace Maize\TenantAware\Listeners;

use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\Support\Config;

class SetTenantKey
{
    public function __invoke(Model $model): void
    {
        if (! method_exists($model, 'setTenantKey')) {
            return;
        }

        $model->setTenantKey(
            Config::getTenantCurrentAction()()
        );
    }
}
