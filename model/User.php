<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 29-Jul-17
 * Time: 12:28
 */

class User implements iEntity
{
    private $id;
    private $login;
    private $password;
    private $games;
    private $wins;
    private $defeats;

    public function __construct($login, $password, $id = null, $hash = false)
    {
        $this->login = $login;
        $this->password = $hash ? $password : password_hash($password, PASSWORD_DEFAULT);
        $this->games = 0;
        $this->wins = 0;
        $this->defeats = 0;
        $this->id = $id;
    }

    /**
     * check if passwords match
     *
     * @param string $password
     * @return bool
     */
    public function comparePassword($password) {
        return password_verify($password, $this->password);
    }


    /**
     * increase games and wins count
     */
    public function win() {
        $this->games++;
        $this->wins++;
        $this->save();
    }

    /**
     * increase games and defeats count
     */
    public function defeat() {
        $this->games++;
        $this->defeats++;
        $this->save();
    }

    /**
     * increase games count
     */
    public function draw() {
        $this->games++;
        $this->save();
    }

    ///////////////////////////////////////
    /// iEntity IMPLEMENTATION
    ///////////////////////////////////////

    public function save() {
        $dbConnector = DBConnector::getInstance();
        if ($this->id) {
            $query = "UPDATE user SET login = ?, password = ?, games = ?, wins = ?, defeats = ? WHERE id = $this->id";
        } else {
            $query = "INSERT INTO user (login, password, games, wins, defeats) VALUES (?, ?, ?, ?, ?)";
        }
        $stmt = $dbConnector->prepare($query);
        $stmt->execute([$this->login, $this->password, $this->games, $this->wins, $this->defeats]);
        if (!$this->id) {
            $this->id = $dbConnector->lastInsertId();
        }
    }

    public function delete() {
        $dbConnector = DBConnector::getInstance();
        $query = "DELETE FROM user WHERE id = $this->id";
        $dbConnector->prepare($query)->execute();
    }

    public static function findById($id) {
        $query = "SELECT * FROM user WHERE id = $id";
        return self::userByQuery($query);
    }
    public static function findBy($parameter, $value) {
        $query = "SELECT * FROM user WHERE $parameter = '$value'";
        return self::userByQuery($query);
    }

    public static function findAll()
    {
        // TODO: Implement findAll() method.
    }

    ///////////////////////////////////////
    /// PRIVATE FUNCTIONS
    ///////////////////////////////////////

    private static function userByQuery($query) {
        $dbConnector = DBConnector::getInstance();
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $userInfo = $stmt->fetch();
        if ($userInfo) {
            $user = new self($userInfo['login'], $userInfo['password'], $userInfo['id'], true);
            $user->games = $userInfo['games'];
            $user->wins = $userInfo['wins'];
            $user->defeats = $userInfo['defeats'];
            return $user;
        }
        return null;
    }

    ///////////////////////////////////////
    /// GETTERS AND SETTERS
    ///////////////////////////////////////

    public function getLogin() {
        return $this->login;
    }

    public function getGames() {
        return $this->games;
    }

    public function getWins() {
        return $this->wins;
    }

    public function getDefeats() {
        return $this->defeats;
    }

    public function getId() {
        return $this->id;
    }

}