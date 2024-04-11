<?php

namespace Maize\TenantAware\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\TenantAware\BelongsToTenant;

class User extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        //
    ];
}
