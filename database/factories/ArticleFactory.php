<?php

namespace Maize\TenantAware\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\Tests\Support\Models\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            //
        ];
    }

    public function tenant(Model $tenant): Factory
    {
        return $this->state(fn (array $attrs) => [
            'tenant_id' => $tenant->getKey(),
        ]);
    }
}
