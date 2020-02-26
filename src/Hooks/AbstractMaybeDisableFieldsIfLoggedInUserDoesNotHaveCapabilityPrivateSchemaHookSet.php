<?php
namespace PoP\UserRolesAccessControl\Hooks;

use PoP\UserRolesAccessControl\Helpers\UserRoleHelper;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\UserState\Hooks\AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet;

abstract class AbstractMaybeDisableFieldsIfLoggedInUserDoesNotHaveCapabilityPrivateSchemaHookSet extends AbstractMaybeDisableFieldsIfUserNotLoggedInPrivateSchemaHookSet
{
    protected function enabled(): bool
    {
        // If the user is not logged-in, then do not enable
        if (!parent::enabled()) {
            return false;
        }

        return !empty($this->getCapabilities());
    }

    /**
     * Decide if to remove the fieldNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(TypeResolverInterface $typeResolver, FieldResolverInterface $fieldResolver, string $fieldName): bool
    {
        $isUserLoggedIn = $this->isUserLoggedIn();

        $capabilities = $this->getCapabilities();

        // Check if the user does not have the required ies
        return !$isUserLoggedIn || !UserRoleHelper::doesCurrentUserHaveAnyCapability($capabilities);
    }

    /**
     * Get the role to validate
     *
     * @return string
     */
    abstract protected function getCapabilities(): array;
}