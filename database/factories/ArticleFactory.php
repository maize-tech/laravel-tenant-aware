<?php

namespace Maize\TenantAware\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maize\TenantAware\Tests\Support\Models\Article;
use Illuminate\Database\Eloquent\Model;

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
