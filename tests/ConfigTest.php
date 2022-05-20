<?php

use Maize\TenantAware\Exceptions\InvalidConfig;
use Maize\TenantAware\Support\Config;

it('throws invalid config exception if null config', function (string $config, string $method) {
    config()->set("tenant-aware.{$config}", null);

    expect(fn () => Config::$method())->toThrow(InvalidConfig::class);
})->with([
    ['config' => 'tenant.model', 'method' => 'getTenantModelName'],
    ['config' => 'tenant.actions.current', 'method' => 'getTenantCurrentAction'],
    ['config' => 'tenant.actions.landlord', 'method' => 'getTenantLandlordAction'],
    ['config' => 'tenant.actions.current_or_landlord', 'method' => 'getTenantCurrentOrLandlordAction'],
    ['config' => 'tenant.actions.only_current', 'method' => 'getTenantOnlyCurrentAction'],
    ['config' => 'scope.apply', 'method' => 'getScopeApply'],
]);
