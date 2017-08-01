<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 29-Jul-17
 * Time: 12:29
 */

class Field implements iEntity
{
    private $id;
    private $field;

    public function __construct($field = null, $id = null)
    {
        $this->field = $field ? $field : [[" ", " ", " "], [" ", " ", " "], [" ", " ", " "]];
        $this->id = $id;
    }

    /**
     * add sign in field
     *
     * @param int $row
     * @param int $column
     * @param string $sign
     */
    public function addSign($row, $column, $sign) {
        $this->field[$row][$column] = $sign;
        $this->save();
    }

    // iEntity IMPLEMENTATION

    public function save() {
        $dbConnector = DBConnector::getInstance();
        if ($this->id) {
            $query = "UPDATE field SET field = ? WHERE id = $this->id";
        } else {
            $query = "INSERT INTO field (field) VALUES (?)";
        }
        $stmt = $dbConnector->prepare($query);
        $stmt->execute([json_encode($this->field)]);
        if (!$this->id) {
            $this->id = $dbConnector->lastInsertId();
        }
    }

    public function delete() {
        $dbConnector = DBConnector::getInstance();
        $query = "DELETE FROM field WHERE id = $this->id";
        $dbConnector->prepare($query)->execute();
    }

    public static function findById($id) {
        $query = "SELECT * FROM field WHERE id = $id";
        return self::fieldByQuery($query);
    }

    public static function findBy($parameter, $value) {
        $query = "SELECT * FROM field WHERE $parameter = $value";
        return self::fieldByQuery($query);
    }

    public static function findAll()
    {
        $dbConnector = DBConnector::getInstance();
        $query = "SELECT * FROM field";
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $fieldsInfo = $stmt->fetchAll();
        if ($fieldsInfo) {
            $fields = [];
            foreach ($fieldsInfo as $fieldInfo) {
                $field = json_decode($fieldInfo['field'], true);
                $fields[] = new Field($field, $fieldInfo['id']);
            }
            return $fields;
        }
        return null;
    }

    // PRIVATE FUNCTIONS

    /**
     * return field by query
     *
     * @param string $query
     * @return Field|null
     */
    private static function fieldByQuery($query) {
        $dbConnector = DBConnector::getInstance();
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $fieldInfo = $stmt->fetch();
        if ($fieldInfo) {
            $field = json_decode($fieldInfo['field'], true);
            return new Field($field, $fieldInfo['id']);
        }
        return null;
    }

    // GETTERS AND SETTERS

    /**
     * @return array
     */
    public function getField() {
        return $this->field;
    }

    /**
     * @param array $newField
     */
    public function setField($newField) {
        $this->field = $newField;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}