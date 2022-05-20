<?php

use Maize\TenantAware\TenantAwareScope;
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
});

it('can be applied global scope', function () {
    expect(
        app(Article::class)->hasGlobalScope(TenantAwareScope::class)
    )->toBeTrue();

    Article::factory()->count(2)->tenant(
        $this->landlord->makeCurrent()
    )->create();

    expect(Article::count())->toBe(2);

    Article::factory(
        $this->tenant->makeCurrent()
    )->create();

    expect(Article::count())->toBe(1);
});
