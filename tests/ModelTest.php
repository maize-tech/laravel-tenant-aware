<?php

use Maize\TenantAware\Tests\Support\Actions\TenantCurrentAction;
use Maize\TenantAware\Tests\Support\Models\Article;
use Maize\TenantAware\Tests\Support\Models\Tenant;

beforeEach(function () {
    config()->set('tenant-aware.tenant.model', Tenant::class);
    config()->set('tenant-aware.tenant.actions.current', TenantCurrentAction::class);

    $this->landlord = Tenant::factory()->landlord()->create();
    $this->tenant = Tenant::factory()->create();

    $this->tenant->makeCurrent();
});

it('can get tenant key name', function (string $key) {
    config()->set('tenant-aware.tenant.foreign_key_name', $key);

    expect(app(Article::class)->getTenantKeyName())->toBe($key);
})->with([
    ['key' => 'tenant_id'],
    ['key' => 'tid'],
]);

it('can get qualified tenant key name', function (string $key) {
    config()->set('tenant-aware.tenant.foreign_key_name', $key);

    expect(app(Article::class)->getQualifiedTenantKeyName())->toBe("articles.{$key}");
})->with([
    ['key' => 'tenant_id'],
    ['key' => 'tid'],
]);

it('can set tenant key on creating model', function (?Tenant $tenant) {
    $tenant?->makeCurrent();

    if (is_null($tenant)) {
        $this->tenant->forgetCurrent();
    }

    $article = Article::factory()->create();

    expect($article->getTenantKey())->toBe($tenant?->getKey());
})->with([
    ['tenant' => fn () => $this->tenant],
    ['tenant' => fn () => $this->landlord],
    ['tenant' => null],
]);

it('can set tenant key on model', function (mixed $key, mixed $result) {
    $article = Article::factory()->create();

    $article->setTenantKey($key);

    expect($article->getTenantKey())->toBe($result);
})->with([
    ['key' => fn () => $this->tenant, 'result' => fn () => $this->tenant->getKey()],
    ['key' => fn () => $this->landlord, 'result' => fn () => $this->landlord->getKey()],
    ['key' => fn () => $this->tenant->getKey(), 'result' => fn () => $this->tenant->getKey()],
    ['key' => fn () => $this->landlord->getKey(), 'result' => fn () => $this->landlord->getKey()],
    ['key' => null, 'result' => null],
]);

it('can get tenant model', function (Tenant $tenant) {
    $tenant->makeCurrent();

    $article = Article::factory()->create();

    expect($article->tenant->getKey())->toBe($tenant->getKey());
})->with([
    ['tenant' => fn () => $this->tenant],
    ['tenant' => fn () => $this->landlord],
]);
