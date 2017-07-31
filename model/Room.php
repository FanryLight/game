<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 29-Jul-17
 * Time: 12:29
 */

class Room implements iEntity
{
    private $id;
    private $userId;
    private $time;

    public function __construct($userId, $time = null, $id = null)
    {
        $this->userId = $userId;
        $this->time = $time ? $time : time();
        $this->id = $id;
    }

    /**
     * set time on now
     */
    public function updateTime() {
        $this->time = time();
    }

    ///////////////////////////////////////
    /// iEntity IMPLEMENTATION
    ///////////////////////////////////////

    public function save() {
        $dbConnector = DBConnector::getInstance();
        if ($this->id) {
            $query = "UPDATE room SET user_id = ?, time = ? WHERE id = $this->id";
        } else {
            $query = "INSERT INTO room (user_id, time) VALUES (?, ?)";
        }
        $stmt = $dbConnector->prepare($query);
        $stmt->execute([$this->userId, $this->time]);
        if (!$this->id) {
            $this->id = $dbConnector->lastInsertId();
        }
    }

    public function delete() {
        $dbConnector = DBConnector::getInstance();
        $query = "DELETE FROM room WHERE id = $this->id";
        $dbConnector->prepare($query)->execute();
    }

    public static function findById($id) {
        $query = "SELECT * FROM room WHERE id = $id";
        return self::roomByQuery($query);
    }

    public static function findBy($parameter, $value) {
        $query = "SELECT * FROM room WHERE $parameter = '$value'";
        return self::roomByQuery($query);
    }

    public static function findAll()
    {
        $dbConnector = DBConnector::getInstance();
        $query = "SELECT * FROM room";
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $roomsInfo = $stmt->fetchAll();
        if ($roomsInfo) {
            $rooms = [];
            foreach ($roomsInfo as $roomInfo) {
                $rooms[] = new Room($roomInfo['user_id'], $roomInfo['time'], $roomInfo['id']);
            }
            return $rooms;
        }
        return null;
    }

    ///////////////////////////////////////
    /// PRIVATE FUNCTIONS
    ///////////////////////////////////////

    private function roomByQuery($query) {
        $dbConnector = DBConnector::getInstance();
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $roomInfo = $stmt->fetch();
        if ($roomInfo) {
            return new Room($roomInfo['user_id'], $roomInfo['time'], $roomInfo['id']);
        }
        return null;
    }



    ///////////////////////////////////////
    /// GETTERS AND SETTERS
    ///////////////////////////////////////

    public function getTime() {
        return $this->time;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getId() {
        return $this->id;
    }

}