<?php

namespace Maize\TenantAware\Actions;

use Illuminate\Database\Eloquent\Model;

class TenantCurrentAction
{
    public function __invoke(): ?Model
    {
        return null;
    }
}
