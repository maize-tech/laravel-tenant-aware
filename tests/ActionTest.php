<?php

use Maize\TenantAware\Actions\TenantCurrentAction;
use Maize\TenantAware\Actions\TenantCurrentOrLandlordAction;
use Maize\TenantAware\Actions\TenantLandlordAction;
use Maize\TenantAware\Actions\TenantOnlyCurrentAction;
use Maize\TenantAware\Tests\Support\Models\Article;
use Maize\TenantAware\Tests\Support\Models\User;

beforeEach(function () {
    config()->set('tenant-aware.models.global', [Article::class]);
});

it('can get null actions', function () {
    expect(app(TenantCurrentAction::class)())->toBeNull();
    expect(app(TenantLandlordAction::class)())->toBeNull();
    expect(app(TenantCurrentOrLandlordAction::class)())->toBeNull();
});

it('can get only current tenant', function (string $model, bool $asModel, bool $request, bool $result) {
    $builder = $model::withoutGlobalScopes();

    if ($asModel) {
        $builder = $builder->getModel();
    }

    if ($request) {
        request()->merge(['tenant.only_current' => null]);
    }

    expect(app(TenantOnlyCurrentAction::class)($builder))->toBe($result);
})->with([
    [User::class, false, true, true],
    [User::class, false, false, true],
    [Article::class, false, true, true],
    [Article::class, false, false, false],
    [User::class, true, true, true],
    [User::class, true, false, true],
    [Article::class, true, true, true],
    [Article::class, true, false, false],
]);
