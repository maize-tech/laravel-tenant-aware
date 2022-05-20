<?php

namespace Maize\TenantAware\Actions;

use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\Support\Config;

class TenantCurrentOrLandlordAction
{
    public function __invoke(): ?Model
    {
        return Config::getTenantCurrentAction()()
            ?? Config::getTenantLandlordAction()();
    }
}
