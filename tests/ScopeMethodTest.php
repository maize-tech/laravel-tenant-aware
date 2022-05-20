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

it('can apply scope tenant where', function (Tenant $current, ?Tenant $tenant = null, int $count = 0, int $articles = 1) {
    $current->makeCurrent();
    Article::factory()->count($articles)->create();

    $models = app(ScopeTenantWhere::class)(
        Article::withoutGlobalScopes(),
        $tenant
    )->get();

    expect($models->count())->toEqual($count);
})->with([
    ['current' => fn () => $this->tenant],
    ['current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 1],
    ['current' => fn () => $this->tenant,  'tenant' => fn () => $this->landlord],
    ['current' => fn () => $this->landlord, 'tenant' => fn () => $this->tenant],
    ['current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 1],
    ['current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 5, 'articles' => 5],
    ['current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 15, 'articles' => 15],
]);

it('can apply scope or tenant where', function (string $condition, Tenant $current, ?Tenant $tenant = null, int $count = 0, int $articles = 1) {
    $current->makeCurrent();
    Article::factory()->count($articles)->create();

    $models = app(ScopeOrTenantWhere::class)(
        Article::withoutGlobalScopes()->whereRaw($condition),
        $tenant
    )->get();

    expect($models->count())->toEqual($count);
})->with([
    ['condition' => '1=0', 'current' => fn () => $this->tenant],
    ['condition' => '1=1', 'current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 1],
    ['condition' => '1=0', 'current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 1],
    ['condition' => '1=1', 'current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 1],
    ['condition' => '1=0', 'current' => fn () => $this->tenant,  'tenant' => fn () => $this->landlord],
    ['condition' => '1=1', 'current' => fn () => $this->tenant,  'tenant' => fn () => $this->landlord, 'count' => 1],
    ['condition' => '1=0', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->tenant],
    ['condition' => '1=1', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->tenant, 'count' => 1],
    ['condition' => '1=0', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 1],
    ['condition' => '1=1', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 1],
    ['condition' => '1=0', 'current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 5, 'articles' => 5],
    ['condition' => '1=1', 'current' => fn () => $this->tenant, 'tenant' => fn () => $this->tenant, 'count' => 5, 'articles' => 5],
    ['condition' => '1=0', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 15, 'articles' => 15],
    ['condition' => '1=1', 'current' => fn () => $this->landlord, 'tenant' => fn () => $this->landlord, 'count' => 15, 'articles' => 15],
]);
