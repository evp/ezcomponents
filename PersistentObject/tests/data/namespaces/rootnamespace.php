<?php

$def = new ezcPersistentObjectDefinition();
$def->table = 'root_namespace';
$def->class = '\\RootNamespace';

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(
    'ezcPersistentSequenceGenerator',
    ['sequence' => 'PO_person_id_seq']
);

return $def;

?>
