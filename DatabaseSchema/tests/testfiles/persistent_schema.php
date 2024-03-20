<?php
return ['ce_bad_word' => 
ezcDbSchemaTable::__set_state(['fields' => 
    ['badword_id' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'substitution' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'word' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false])], 'indexes' => 
    []]), 'ce_message_category_rel' => 
ezcDbSchemaTable::__set_state(['fields' => 
    ['category_id' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'is_shadow' => 
ezcDbSchemaField::__set_state(['type' => 'boolean', 'length' => 0, 'notNull' => false, 'default' => false, 'autoIncrement' => false, 'unsigned' => false]), 'message_id' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false])], 'indexes' => 
    []]), 'debugger' => 
ezcDbSchemaTable::__set_state(['fields' => 
    ['session_id' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false])], 'indexes' => 
    []]), 'liveuser_translations' => 
ezcDbSchemaTable::__set_state(['fields' => 
    ['description' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'language_id' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'name' => 
ezcDbSchemaField::__set_state(['type' => 'text', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'section_id' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'section_type' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => false, 'default' => NULL, 'autoIncrement' => false, 'unsigned' => false]), 'translation_id' => 
ezcDbSchemaField::__set_state(['type' => 'integer', 'length' => 0, 'notNull' => true, 'default' => '0', 'autoIncrement' => true, 'unsigned' => false])], 'indexes' => 
    [0 => 
ezcDbSchemaIndex::__set_state(['indexFields' => 
        ['translation_id' => 
ezcDbSchemaIndexField::__set_state(['sorting' => NULL])], 'primary' => true, 'unique' => false])]])];

?>
