<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 27-Jul-17
 * Time: 12:57
 */

class SessionManager
{
    private static $instance;

    /**
     * @return SessionManager
     */
    public static function getInstance() {
        if (isset(self::$instance)) {
            return self::$instance;
        }
        return new self();
    }

    private function __construct()
    {
    }

    /**
     * start session
     *
     * @param int $userId
     */
    public function start($userId = null) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['userId']) && $userId) {
            $_SESSION['userId'] = $userId;
        }
    }

    /**
     * close session
     */
    public function close() {
        if (isset($_SESSION['userId'])) {
            $_SESSION['userId'] = null;
            session_write_close();
        }
    }

    /**
     * check if user is logged in
     *
     * @return bool
     */
    public function isLoggedIn() {
        return isset($_SESSION['userId']);
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $_SESSION['userId'];
    }
}