<?php

namespace Maize\TenantAware\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        //
    ];

    public function makeCurrent(): self
    {
        config()->set(
            'tenant-aware.testing.current_tenant',
            $this->name
        );

        return $this;
    }

    public function forgetCurrent(): self
    {
        config()->set(
            'tenant-aware.testing.current_tenant',
            null
        );

        return $this;
    }
}
