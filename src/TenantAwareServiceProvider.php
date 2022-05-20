<?php

namespace Maize\TenantAware;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TenantAwareServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-tenant-aware')
            ->hasConfigFile();
    }
}
