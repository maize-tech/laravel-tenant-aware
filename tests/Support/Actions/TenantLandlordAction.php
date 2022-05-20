<?php

namespace Maize\TenantAware\Tests\Support\Actions;

use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\Tests\Support\Models\Tenant;

class TenantLandlordAction
{
    public function __invoke(): ?Model
    {
        return Tenant::firstWhere('name', 'landlord');
    }
}
