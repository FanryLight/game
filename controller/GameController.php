<?php


class GameController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Route('/game')
     */
    public function indexAction() {
        if ($this->getSession()->isLoggedIn()) {
            $game = Game::findBy('user_id', $this->getSession()->getUserId());
            if ($game) {
                $user = User::findById($this->getSession()->getUserId());
                $opponent = User::findById($game->getOpponentId());
                $data['turn'] = $game->getTurn();
                $data['sign'] = $game->getSign();
                $data['userLogin'] = $user->getLogin();
                $data['opponentLogin'] = $opponent->getLogin();
                $field = Field::findById($game->getFieldId());
                $data['field'] = $field->getField();
                $this->getView()->generate('game', null, $data);
            } else {
                $this->redirectTo('/');
            }
        } else {
            $this->redirectTo('/authorization');
        }
    }

    /**
     * Route('/game/move')
     */
    public function moveAction() {
        if ($this->getSession()->isLoggedIn()) {
            $game = Game::findBy('user_id', $this->getSession()->getUserId());
            if ($game && $game->getTurn()) {
                $row = $_POST['row'];
                $column = $_POST['column'];
                $sign = $game->getSign();
                $field = Field::findById($game->getFieldId());
                $field->addSign($row, $column, $sign);
                $this->changeTurns($game);
                $user = User::findById($this->getSession()->getUserId());
                if ($this->didWin($field, $row, $column, $sign)) {
                    $game->changeStatus(Game::GAME_STATUS_WIN);
                    $game->delete();
                    $user->win();
                    echo 'win';
                } elseif ($this->isDraw($field)) {
                    $game->changeStatus(Game::GAME_STATUS_DRAW);
                    $game->delete();
                    $user->draw();
                    echo 'draw';
                } else {
                    echo 'success';
                }
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    }

    /**
     * Route('/game/check')
     */
    public function checkAction() {
        if ($this->getSession()->isLoggedIn()) {
            $game = Game::findBy('user_id', $this->getSession()->getUserId());
            if ($game) {
                $status = $game->getStatus();
                $user = User::findById($this->getSession()->getUserId());
                if ($status === Game::GAME_STATUS_DRAW) {
                    $game->delete();
                    $user->draw();
                    $field = Field::findById($game->getFieldId());
                    $field->delete();
                } elseif ($status === Game::GAME_STATUS_DEFEAT) {
                    $game->delete();
                    $user->defeat();
                    $field = Field::findById($game->getFieldId());
                    $field->delete();
                }
                echo $status;
            } else {
                echo 'error';
            }
        } else {
            echo 'error';
        }
    }

    /**
     * check if game ended as draw
     *
     * @param Field $field
     * @return bool
     */
    private function isDraw(Field $field) {
        $fieldArray = $field->getField();
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($fieldArray[$i][$j] === " ") {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * check if user won
     *
     * @param Field $field
     * @param int $row
     * @param int $column
     * @param string $sign
     * @return bool
     */
    private function didWin(Field $field, $row, $column, $sign) {
        $fieldArray = $field->getField();
        if ($this->horizontal($fieldArray, $row, $sign) ||
            $this->vertical($fieldArray, $column, $sign) ||
            $this->diagonal($fieldArray, $row, $column, $sign)
        ) {
            return true;
        }
        return false;
    }

    /**
     * look for combination on diagonal
     *
     * @param array $fieldArray
     * @param int $row
     * @param int $column
     * @param string $sign
     * @return bool
     */
    private function diagonal($fieldArray, $row, $column, $sign) {
        if (($row % 2 == 0 && $column % 2 == 0) || ($row % 2 != 0 && $column % 2 != 0)) {
            $win = true;
            for ($i = 0; $i < 3; $i++) {
                if ($fieldArray[$i][$i] !== $sign) {
                    $win = false;
                    break;
                }
            }
            if ($win) {
                return $win;
            } else {
                $win = true;
                for ($i = 0; $i < 3; $i++) {
                    if ($fieldArray[$i][2 - $i] !== $sign) {
                        $win = false;
                        break;
                    }
                }
                return $win;
            }
        }
        return false;
    }

    /**
     * look for combination vertically
     *
     * @param array $fieldArray
     * @param int $column
     * @param string $sign
     * @return bool
     */
    private function vertical($fieldArray, $column, $sign) {
        for ($i = 0; $i < 3; $i++) {
            if ($fieldArray[$i][$column] !== $sign) {
                return false;
            }
        }
        return true;
    }

    /**
     * look for combination horizontal
     *
     * @param array $fieldArray
     * @param int $row
     * @param string $sign
     * @return bool
     */
    private function horizontal($fieldArray, $row, $sign) {
        for ($i = 0; $i < 3; $i++) {
            if ($fieldArray[$row][$i] !== $sign) {
                return false;
            }
        }
        return true;
    }

    /**
     * change turns of players
     *
     * @param Game $game
     */
    private function changeTurns(Game $game) {
        $game->changeTurn();
        $opponentGame = Game::findBy('user_id', $game->getOpponentId());
        $opponentGame->changeTurn();
    }
}