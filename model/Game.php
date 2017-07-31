<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 29-Jul-17
 * Time: 12:29
 */

class Game implements iEntity
{
    const GAME_STATUS_NONE = 'none';
    const GAME_STATUS_WIN = 'win';
    const GAME_STATUS_DEFEAT = 'defeat';
    const GAME_STATUS_DRAW = 'draw';

    private $id;
    private $userId;
    private $opponentId;
    private $turn;
    private $sign;
    private $fieldId;
    private $status;

    public function __construct($userId, $opponentId, $turn, $sign, $fieldId, $id = null, $status = null)
    {
        $this->userId = $userId;
        $this->opponentId = $opponentId;
        $this->turn = $turn;
        $this->sign = $sign;
        $this->fieldId = $fieldId;
        $this->id = $id;
        $this->status = $status ? $status : Game::GAME_STATUS_NONE;
    }


    /**
     * change turn
     */
    public function changeTurn() {
        $this->turn = !$this->turn;
        $this->save();
    }


    /**
     * change game status of itself and opponent
     *
     * @param string $status
     */
    public function changeStatus($status) {
        $this->status = $status;
        $opponentGame = Game::findBy('user_id', $this->getOpponentId());
        if ($status === Game::GAME_STATUS_WIN) {
            $opponentStatus = Game::GAME_STATUS_DEFEAT;
        } elseif ($status === Game::GAME_STATUS_DEFEAT) {
            $opponentStatus = Game::GAME_STATUS_WIN;
        } elseif ($status === Game::GAME_STATUS_DRAW) {
            $opponentStatus = Game::GAME_STATUS_DRAW;
        }
        $opponentGame->setStatus($opponentStatus);
        $this->save();
        $opponentGame->save();
    }

    ///////////////////////////////////////
    /// iEntity IMPLEMENTATION
    ///////////////////////////////////////

    public function save() {
        $dbConnector = DBConnector::getInstance();
        if ($this->id) {
            $query = "UPDATE game SET user_id = ?, opponent_id = ?, turn = ?, sign = ?, field_id = ?, status = ? WHERE id = $this->id";
        } else {
            $query = "INSERT INTO game (user_id, opponent_id, turn, sign, field_id, status) VALUES (?, ?, ?, ?, ?, ?)";
        }
        $stmt = $dbConnector->prepare($query);
        $stmt->execute([$this->userId, $this->opponentId, $this->turn, $this->sign, $this->fieldId, $this->status]);
        if (!$this->id) {
            $this->id = $dbConnector->lastInsertId();
        }
    }

    public function delete() {
        $dbConnector = DBConnector::getInstance();
        $query = "DELETE FROM game WHERE id = $this->id";
        $dbConnector->prepare($query)->execute();
    }

    public static function findById($id) {
        $query = "SELECT * FROM game WHERE id = $id";
        return self::gameByQuery($query);
    }

    public static function findBy($parameter, $value) {
        $query = "SELECT * FROM game WHERE $parameter = $value";
        return self::gameByQuery($query);
    }

    public static function findAll()
    {
        // TODO: Implement findAll() method.
    }

    ///////////////////////////////////////
    /// PRIVATE FUNCTIONS
    ///////////////////////////////////////

    /**
     * @param string $query
     * @return Game|null
     */
    private static function gameByQuery($query) {
        $dbConnector = DBConnector::getInstance();
        $stmt = $dbConnector->query($query);
        if (!$stmt) {
            return null;
        }
        $gameInfo = $stmt->fetch();
        if ($gameInfo) {
            return new Game($gameInfo['user_id'],
                $gameInfo['opponent_id'],
                $gameInfo['turn'],
                $gameInfo['sign'],
                $gameInfo['field_id'],
                $gameInfo['id'],
                $gameInfo['status']);
        }
        return null;
    }

    ///////////////////////////////////////
    /// GETTERS AND SETTERS
    ///////////////////////////////////////

    /**
     * @return string
     */
    public function getSign() {
        return $this->sign;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOpponentId()
    {
        return $this->opponentId;
    }

    /**
     * @return int
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @return bool
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

}