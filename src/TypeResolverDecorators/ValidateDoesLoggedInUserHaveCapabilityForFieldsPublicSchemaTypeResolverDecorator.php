<?php
namespace PoP\UserRolesAccessControl\TypeResolverDecorators;

use PoP\UserRolesAccessControl\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoP\UserRoles\Conditional\UserState\DirectiveResolvers\ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver;
use PoP\UserStateAccessControl\TypeResolverDecorators\ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

class ValidateDoesLoggedInUserHaveCapabilityForFieldsPublicSchemaTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    use ValidateConditionForFieldsPublicSchemaTypeResolverDecoratorTrait;

    protected static function getConfiguredEntryList(): array
    {
        return ComponentConfiguration::getRestrictedFieldsByUserCapability();
    }

    /**
     * By default, only the admin can see the roles from the users
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $configuredEntryList = ComponentConfiguration::getRestrictedFieldsByUserCapability();
        $directiveName = ValidateDoesLoggedInUserHaveAnyCapabilityDirectiveResolver::getDirectiveName();
        // Obtain all capabilities allowed for the current combination of typeResolver/fieldName
        foreach ($this->getFieldNames() as $fieldName) {
            if ($matchingEntries = $this->getMatchingEntriesFromConfiguration(
                $configuredEntryList,
                $typeResolver,
                $fieldName
            )) {
                if ($capabilities = array_values(array_unique(array_map(
                    function($entry) {
                        return $entry[2];
                    },
                    $matchingEntries
                )))) {
                    $validateDoesLoggedInUserHaveAnyRoleDirective = $fieldQueryInterpreter->getDirective(
                        $directiveName,
                        [
                            'capabilities' => $capabilities,
                        ]
                    );
                    $mandatoryDirectivesForFields[$fieldName] = [
                        $validateDoesLoggedInUserHaveAnyRoleDirective,
                    ];
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
}
