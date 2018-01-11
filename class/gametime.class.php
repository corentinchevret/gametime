<?php

class GameTime extends SeedObject
{

    function __construct(&$db)
    {

        $this->db = $db;

        $this->table_element = 'gametime';

        $this->fields = array(
            'rowid' => array('type' => 'integer', 'index' => true)
        , 'title' => array('type' => 'string', 'index' => true, 'length' => 80)
        , 'time' => array('type' => 'integer', 'index' => true)
        , 'fk_contact' => array('type' => 'integer', 'index' => true)
        , 'fk_soc' => array('type' => 'integer', 'index' => true)
        , 'date_creation' => array('type' => 'date')
        , 'tms' => array('type' => 'date')

        );

        $result = $this->call_trigger('ORDER_VALIDATE', $user);
        if ($result < 0) $error++;

        $this->init();
    }

    function fetchByContact($fk_contact)
    {

        $res = $this->db->query("SELECT rowid FROM " . MAIN_DB_PREFIX . $this->table_element . " 
			WHERE fk_contact=" . (int)$fk_contact);
        if ($obj = $this->db->fetch_object($res)) {
            return $this->fetchCommon($obj->rowid);
        }

        return false;
    }
}