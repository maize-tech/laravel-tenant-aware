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

it('can set tenant key on creating model', function (?string $which) {
    $tenant = $which ? $this->{$which} : null;

    $tenant?->makeCurrent();

    if (is_null($tenant)) {
        $this->tenant->forgetCurrent();
    }

    $article = Article::factory()->create();

    expect($article->getTenantKey())->toBe($tenant?->getKey());
})->with([
    ['tenant'],
    ['landlord'],
    [null],
]);

it('can set tenant key on model', function (?string $which, bool $asKey) {
    $article = Article::factory()->create();

    $tenant = $which ? $this->{$which} : null;

    $article->setTenantKey($asKey ? $tenant?->getKey() : $tenant);

    expect($article->getTenantKey())->toBe($tenant?->getKey());
})->with([
    ['tenant', false],
    ['landlord', false],
    ['tenant', true],
    ['landlord', true],
    [null, false],
]);

it('can get tenant model', function (string $which) {
    $tenant = $this->{$which};

    $tenant->makeCurrent();

    $article = Article::factory()->create();

    expect($article->tenant->getKey())->toBe($tenant->getKey());
})->with([
    ['tenant'],
    ['landlord'],
]);
