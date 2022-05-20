<?php

namespace Maize\TenantAware\Tests\Support\Actions;

use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\Tests\Support\Models\Tenant;

class TenantCurrentAction
{
    public function __invoke(): ?Model
    {
        return Tenant::firstWhere('name', config('tenant-aware.testing.current_tenant'));
    }
}
