<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 27-Jul-17
 * Time: 13:14
 */

class RoomController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Route('/room')
     */
    public function indexAction() {
        if ($this->getSession()->isLoggedIn()) {
            if (!$room = Room::findBy('user_id', $this->getSession()->getUserId())) {
                $room = new Room($this->getSession()->getUserId());
                $room->save();
            }
            $this->getView()->generate('room', 'Waiting for opponent');
        } else {
            $this->redirectTo('/authorization');
        }
    }

    /**
     * Route('/room/check')
     */
    public function checkAction() {
        if ($this->getSession()->isLoggedIn()) {
            $userId = $this->getSession()->getUserId();
            if ($room = Room::findBy('user_id', $userId)) {
                $room->updateTime();
                if ($this->findOpponent($room)) {
                    echo 'game';
                    return;
                }
                echo 'wait';
            } elseif (Game::findBy('user_id', $userId)) {
                echo 'game';
                return;
            }
        } else {
            echo 'error';
        }
    }

    /**
     * look for opponent
     *
     * @param Room $myRoom
     * @return bool
     */
    private function findOpponent(Room $myRoom) {
        $rooms = Room::findAll();
        if (count($rooms) > 1) {
            foreach ($rooms as $room) {
                if ($room->getUserId() != $myRoom->getUserId()) {
                    if ($room->getTime() - time() < 3) {
                        $field = new Field();
                        $field->save();
                        $game = new Game($myRoom->getUserId(),
                            $room->getUserId(),
                            true,
                            "o",
                            $field->getId());
                        $game->save();
                        $game = new Game($room->getUserId(),
                            $myRoom->getUserId(),
                            false,
                            "x",
                            $field->getId());
                        $game->save();
                        $room->delete();
                        $myRoom->delete();
                        return true;
                    } else {
                        $room->delete();
                    }
                }
            }
        }
        return false;
    }
}