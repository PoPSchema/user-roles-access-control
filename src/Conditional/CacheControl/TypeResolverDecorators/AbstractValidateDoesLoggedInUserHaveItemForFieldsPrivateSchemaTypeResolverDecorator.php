<?php

declare(strict_types=1);

namespace PoP\UserRolesAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator;
use PoP\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators\NoCacheConfigurableAccessControlTypeResolverDecoratorTrait;

abstract class AbstractValidateDoesLoggedInUserHaveItemForFieldsPrivateSchemaTypeResolverDecorator extends AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlTypeResolverDecoratorTrait;
}
