<?php

namespace Maize\TenantAware\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maize\TenantAware\Tests\Support\Models\Tenant;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }

    public function landlord(): Factory
    {
        return $this->state(fn (array $attrs) => [
            'name' => 'landlord',
        ]);
    }
}
