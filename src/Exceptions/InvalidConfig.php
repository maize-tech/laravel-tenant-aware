<?php

namespace Maize\TenantAware\Exceptions;

use Exception;

class InvalidConfig extends Exception
{
    public static function couldNotDetermineTenantModelName(): self
    {
        return new self('Could not determine the tenant model name. Make sure you specified a valid tenant model in the tenant aware config file');
    }

    public static function couldNotDetermineTenantCurrentActionName(): self
    {
        return new self('Could not determine the tenant current action name. Make sure you specified a valid tenant current action in the tenant aware config file');
    }

    public static function couldNotDetermineTenantLandlordActionName(): self
    {
        return new self('Could not determine the tenant landlord action name. Make sure you specified a valid tenant landlord action in the tenant aware config file');
    }

    public static function couldNotDetermineTenantCurrentOrLandlordActionName(): self
    {
        return new self('Could not determine the tenant current or landlord action name. Make sure you specified a valid tenant current or landlord action in the tenant aware config file');
    }

    public static function couldNotDetermineScopeApplyName(): self
    {
        return new self('Could not determine the scope apply name. Make sure you specified a valid scope apply in the tenant aware config file');
    }

    public static function couldNotDetermineTenantOnlyCurrentActionName(): self
    {
        return new self('Could not determine the tenant only current action name. Make sure you specified a valid tenant only current action in the tenant aware config file');
    }
}
