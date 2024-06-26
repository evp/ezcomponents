<?php
require_once __DIR__ . "/relation_test.php";

class RelationTestBirthday extends RelationTest
{
    public $person   = null;
    public $birthday = null;


    public function setState( array $state )
    {
        foreach ( $state as $key => $value )
        {
            $this->$key = $value;
        }
    }

    public function getState()
    {
        return ["person"   => $this->person, "birthday" => $this->birthday];
    }

    public static function __set_state( array $state )
    {
        $birthday = new RelationTestBirthday();
        foreach ( $state as $key => $value )
        {
            $birthday->$key = $value;
        }
        return $birthday;
    }
}

?>
