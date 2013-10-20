<?php

class Application_Model_DbTable_Ad extends Zend_Db_Table_Abstract
{
    const STATUS_DRAFT = "DRAFT";
    const STATUS_READY = "READY";
    const STATUS_ACTIVE = "ACTIVE";
    const STATUS_ARCHIVE = "ARCHIVE";

    protected $_name = 'ads';

    public function save($data, $id) {
        try {
            if (!empty($id)) {
                $res = $this->update($data, 'id = '. (int)$id);
                $res = $id;
            } else {
                if (isset($data["id"]))
                    unset($data["id"]);
                $res = $this->insert($data);
            }
            return $res;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function get($id)
    {
        try {
            $res = $this->find($id);
        } catch (Exception $e) {
            return false;
        }

        $res = $res->getRow(0)->toArray();
        return $res;
    }
}

