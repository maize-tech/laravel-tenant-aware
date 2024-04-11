<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

it('can get only current tenant', function (Model|Builder $builder, bool $request, bool $result) {
    if ($request) {
        request()->merge(['tenant.only_current' => null]);
    }

    expect(app(TenantOnlyCurrentAction::class)($builder))->toBe($result);
})->with([
    ['builder' => fn () => User::withoutGlobalScopes(), 'request' => true, 'result' => true],
    ['builder' => fn () => User::withoutGlobalScopes(), 'request' => false, 'result' => true],
    ['builder' => fn () => Article::withoutGlobalScopes(), 'request' => true, 'result' => true],
    ['builder' => fn () => Article::withoutGlobalScopes(), 'request' => false, 'result' => false],
    ['builder' => fn () => User::withoutGlobalScopes()->getModel(), 'request' => true, 'result' => true],
    ['builder' => fn () => User::withoutGlobalScopes()->getModel(), 'request' => false, 'result' => true],
    ['builder' => fn () => Article::withoutGlobalScopes()->getModel(), 'request' => true, 'result' => true],
    ['builder' => fn () => Article::withoutGlobalScopes()->getModel(), 'request' => false, 'result' => false],
]);
