<?php

namespace Maize\TenantAware;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maize\TenantAware\Support\Config;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantAwareScope());

        static::creating(
            Config::getModelCreatingListener()
        );
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(
            Config::getTenantModelName()
        );
    }

    public function setTenantKey(Model|int|null $tenant): void
    {
        if ($tenant instanceof Model) {
            $tenant = $tenant->getKey();
        }
        $this->setAttribute(
            $this->getTenantKeyName(),
            $tenant
        );
    }

    public function getQualifiedTenantKeyName(): string
    {
        return $this->qualifyColumn(
            $this->getTenantKeyName()
        );
    }

    public function getTenantKey(): mixed
    {
        return $this->getAttribute(
            $this->getTenantKeyName()
        );
    }

    public function getTenantKeyName(): string
    {
        return Config::getTenantForeignKeyName();
    }
}
