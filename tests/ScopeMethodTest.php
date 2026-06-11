<?php

use Maize\TenantAware\Scopes\ScopeOrTenantWhere;
use Maize\TenantAware\Scopes\ScopeTenantWhere;
use Maize\TenantAware\Tests\Support\Actions\TenantCurrentAction;
use Maize\TenantAware\Tests\Support\Actions\TenantLandlordAction;
use Maize\TenantAware\Tests\Support\Models\Article;
use Maize\TenantAware\Tests\Support\Models\Tenant;

beforeEach(function () {
    config()->set('tenant-aware.tenant.model', Tenant::class);
    config()->set('tenant-aware.tenant.actions.current', TenantCurrentAction::class);
    config()->set('tenant-aware.tenant.actions.landlord', TenantLandlordAction::class);

    $this->landlord = Tenant::factory()->landlord()->create();
    $this->tenant = Tenant::factory()->create();

    $this->tenant->makeCurrent();
});

it('can apply scope tenant where', function (string $current, ?string $tenant = null, int $count = 0, int $articles = 1) {
    $this->{$current}->makeCurrent();
    Article::factory()->count($articles)->create();

    $models = app(ScopeTenantWhere::class)(
        Article::withoutGlobalScopes(),
        $tenant ? $this->{$tenant} : null
    )->get();

    expect($models->count())->toEqual($count);
})->with([
    ['tenant'],
    ['tenant', 'tenant', 1],
    ['tenant', 'landlord'],
    ['landlord', 'tenant'],
    ['landlord', 'landlord', 1],
    ['tenant', 'tenant', 5, 5],
    ['landlord', 'landlord', 15, 15],
]);

it('can apply scope or tenant where', function (string $condition, string $current, ?string $tenant = null, int $count = 0, int $articles = 1) {
    $this->{$current}->makeCurrent();
    Article::factory()->count($articles)->create();

    $models = app(ScopeOrTenantWhere::class)(
        Article::withoutGlobalScopes()->whereRaw($condition),
        $tenant ? $this->{$tenant} : null
    )->get();

    expect($models->count())->toEqual($count);
})->with([
    ['1=0', 'tenant'],
    ['1=1', 'tenant', 'tenant', 1],
    ['1=0', 'tenant', 'tenant', 1],
    ['1=1', 'tenant', 'tenant', 1],
    ['1=0', 'tenant', 'landlord'],
    ['1=1', 'tenant', 'landlord', 1],
    ['1=0', 'landlord', 'tenant'],
    ['1=1', 'landlord', 'tenant', 1],
    ['1=0', 'landlord', 'landlord', 1],
    ['1=1', 'landlord', 'landlord', 1],
    ['1=0', 'tenant', 'tenant', 5, 5],
    ['1=1', 'tenant', 'tenant', 5, 5],
    ['1=0', 'landlord', 'landlord', 15, 15],
    ['1=1', 'landlord', 'landlord', 15, 15],
]);
