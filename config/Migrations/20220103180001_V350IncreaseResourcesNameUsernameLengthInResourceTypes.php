<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class V350IncreaseResourcesNameUsernameLengthInResourceTypes extends AbstractMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $resourceTypesTableName = $this->getAdapter()->quoteTableName('resource_types');
        $definitionColumnName = $this->getAdapter()->quoteColumnName('definition');
        $idColumnName = $this->getAdapter()->quoteColumnName('id');

        $resourceTypes = $this->fetchAll("SELECT * FROM $resourceTypesTableName");

        foreach($resourceTypes as $resourceType) {
            $updatedResourceTypeDefinition = $this->updateResourceTypeDefinition($resourceType['definition']);
            $this->execute("UPDATE $resourceTypesTableName SET $definitionColumnName='{$updatedResourceTypeDefinition}' WHERE $idColumnName='{$resourceType['id']}';");
        }
    }

    /**
     * Update the resource type definition json schema.
     * @param string $resourceTypeDefinition The resource type definition to update.
     * @return string
     */
    private function updateResourceTypeDefinition(string $resourceTypeDefinition): string
    {
        $resourceTypeDefinitionDecoded = json_decode($resourceTypeDefinition, true);

        // Update name property definition.
        $resourceTypeDefinitionDecoded['resource']['properties']['name'] = [
            "type" => "string",
            "maxLength" => 255
        ];

        // Update username property definition.
        $resourceTypeDefinitionDecoded['resource']['properties']['username'] = [
            "anyOf" => [[
                "type" => "string",
                "maxLength" => 255
            ], [
                "type" => "null"
            ]]
        ];

        return json_encode($resourceTypeDefinitionDecoded);
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
    }
}
